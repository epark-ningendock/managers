<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\HospitalImage;
use App\Http\Requests\HospitalImageFormRequest;
use App\ImageOrder;
use App\HospitalCategory;
use App\Lock;
use App\InterviewDetail;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\File;
use Illuminate\Support\Facades\DB;
use Reshadman\OptimisticLocking\StaleModelLockingException;
use App\Enums\ImageGroupNumber;

class HospitalImagesController extends Controller
{
    public function __construct(
        HospitalImage $hospital_image,
        HospitalCategory $hospital_category,
        ImageOrder $image_order,
        InterviewDetail $interview_detail,
        Lock $lock
    )
    {
        $this->hospital_image = $hospital_image;
        $this->hospital_category = $hospital_category;
        $this->image_order = $image_order;
        $this->interview_detail = $interview_detail;
        $this->lock = $lock;
        $this->sp_dir = 'SP';
        $this->base_name = $baseClass = class_basename(HospitalImage::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($hospital_id)
    {
        $hospital = Hospital::with(['hospital_images', 'hospital_categories', 'lock'])->find($hospital_id);

        $select_photos = $hospital->hospital_categories()->where('is_display', 1)->where('hospital_id', $hospital_id)->get();
        //dd($select_photos->where('order', 1)->first()->hospital_image);



        $interview_top = $hospital->hospital_categories()->where('image_order', ImageGroupNumber::IMAGE_GROUP_INTERVIEW)->first();

        //interviewのタイトルなどの情報が必要
        if(is_null($interview_top)){
            $save_sub_images = ['extension' => 'dummy', 'name' => 'dummy', 'path' => null, 'memo1' => 'dummy'];
            $hospital_dummy_img = $hospital->hospital_images()->saveMany([
                    new HospitalImage($save_sub_images)
                ]
            );
            $hospital->hospital_categories()->create(
                ['hospital_image_id' => $hospital_dummy_img[0]->id,'image_order' => ImageGroupNumber::IMAGE_GROUP_INTERVIEW,'image_order' => ImageGroupNumber::IMAGE_GROUP_INTERVIEW,'order' => 1]
            );
        }

        $interviews = $hospital->hospital_categories()->where('image_order', ImageGroupNumber::IMAGE_GROUP_INTERVIEW)->first()->interview_details()->interviewOrder()->get();

        $image_order = $this->image_order;

        $hospital_category = $this->hospital_category;

        $tab_name_list = [ 1 => 'スタッフ',  2 => '設備',  3 => '院内' , 4 => '外観',  5 => 'その他'];

        return view('hospital.create-images', compact('hospital', 'hospital_id', 'image_order', 'tab_name_list', 'interview_top', 'interviews', 'hospital_category','select_photos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HospitalImageFormRequest $request, int $hospital_id)
    {
        $file = $request->all();

        $hospital = Hospital::find($hospital_id);

        //dd($file['select_photo']);
        foreach($file['select_photo'] as $key => $select_photo) {
            if(is_null($select_photo)) continue;
            $hospital_image = $this->hospital_category->byHospitalImageId(intval($select_photo))->first();

            // todo is_display 1 enumにする
            $this->hospital_category->updateOrCreate(
                ['is_display' => 1,'order' => $key,'hospital_id' => $hospital_id,'image_order' => 9],
                [
                    'hospital_image_id' => intval($select_photo),
                    'is_display' => 1,
                    'order' => $key,
                    'hospital_id' => $hospital_id,
                    'image_order' => 9,//todo const
                    //'file_location_no' => $hospital_image->file_location_no,
                ]
            );
        }

        //排他的制御。
        $lock_flag = $this->isLockVersionTrue('HospitalImage',$hospital,$file['lock_version']);
        if(!$lock_flag) {
            return redirect()->back()->with('error', trans('messages.model_changed_error'));
        }

        //手動でLockを変更する
        DB::beginTransaction();
        $this->lock->updateOrCreate(
            ['hospital_id' => $hospital_id,'model' => 'HospitalImage'],
            [
                'token' => str_random(32),
                'model' => 'HospitalImage',
                'hospital_id' => $hospital_id,
            ]
        );
        try {
            //TOPの保存
            $hospital->hospital_categories()->updateOrCreate(
                ['hospital_id' => $hospital_id,'image_order' => ImageGroupNumber::IMAGE_GROUP_TOP],
                [
                    'hospital_id' => $hospital_id,
                    'image_order' => ImageGroupNumber::IMAGE_GROUP_TOP,
                    'title' => $file['title'],
                    'caption' => $file['caption'],
                    'order2' => 1
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.update_error'));
        }

        //main画像の保存
        if(isset($file['main'])) {
            $img_info = $this->putFileStorageImage($file['main'], $hospital_id, true);

            //ファイル拡張子取得
            $extension = $file['main']->getClientOriginalExtension();
            //hospital_categories table 保存情報のセット
            $save_images = ['extension' => $extension, 'name' => $img_info['pc_img_name'], 'path' => $img_info['pc_img_url']];
            $save_image_categories = [ 'hospital_id' => $hospital_id, 'image_order' => ImageGroupNumber::IMAGE_GROUP_FACILITY_MAIN, 'file_location_no' => 1 ];
            $save_images_sp = ['extension' => $extension, 'name' => $img_info['sp_img_name'], 'path' => $img_info['sp_img_url']];
            $save_image_categories_sp = [ 'hospital_id' => $hospital_id, 'image_order' => ImageGroupNumber::IMAGE_GROUP_FACILITY_MAIN, 'file_location_no' => 2 ];

            //メイン画像の登録確認$hospital_id, $image_order, $i, $location_no
            $image_category_pc = $this->hospital_category->byImageOrderAndFileLocationNo($hospital_id, ImageGroupNumber::IMAGE_GROUP_FACILITY_MAIN, 0, 1)->first();

            $image_category_sp = $this->hospital_category->byImageOrderAndFileLocationNo($hospital_id, ImageGroupNumber::IMAGE_GROUP_FACILITY_MAIN, 0, 2)->first();

            $this->saveImageAndDeleteOldImage ($hospital,$image_category_pc,$save_images,$save_image_categories);
            $this->saveImageAndDeleteOldImage ($hospital,$image_category_sp,$save_images_sp,$save_image_categories_sp);
        }

        //sub
        for($i = 1; $i <= 4; $i++){
            if(isset($file['sub_'.$i])) {
                $this->hospitalImageUploader($file, 'sub_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_FACILITY_SUB);
            }
        }
        //こだわり
        for($i = 1; $i <= 4; $i++){
            if(isset($file['speciality_'.$i]) or isset($file['speciality_'.$i.'_title']) or isset($file['speciality_'.$i.'_caption'])) {
                $this->hospitalImageUploader($file, 'speciality_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_SPECIALITY,null,null,null,$file['speciality_'.$i.'_title'],$file['speciality_'.$i.'_caption'] );
            }
        }
        //スタッフ
        for($i = 1; $i <= 10; $i++){
            if(isset($file['staff_'.$i.'_category_id']) or isset($file['staff_'.$i]) or isset($file['staff_'.$i.'_name']) or isset($file['staff_'.$i.'_career']) or isset($file['staff_'.$i.'_memo'])) {
            $this->hospitalImageUploader($file, 'staff_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_STAFF,$file['staff_'.$i.'_name'],$file['staff_'.$i.'_career'],$file['staff_'.$i.'_memo'] );
            }
        }
        //タブ staff
        for($i = 1; $i <= 30; $i++){
            if(isset($file['staff_tab_'.$i]) or isset($file['staff_tab_'.$i.'_category_id']) or isset($file['staff_tab_'.$i.'_memo2']) or isset($file['staff_tab_'.$i.'_order2'])) {
                $this->hospitalImageUploader($file, 'staff_tab_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_TAB);
            }
        }
        //タブ facility
        for($i = 1; $i <= 30; $i++){
            if(isset($file['facility_tab_'.$i]) or isset($file['facility_tab_'.$i.'_category_id']) or isset($file['facility_tab_'.$i.'_memo2']) or isset($file['facility_tab_'.$i.'_order2'])) {
                $this->hospitalImageUploader($file, 'facility_tab_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_TAB);
            }
        }

        //タブ internal
        for($i = 1; $i <= 30; $i++){
            if(isset($file['internal_tab_'.$i]) or isset($file['internal_tab_'.$i.'_category_id']) or isset($file['internal_tab_'.$i.'_memo2']) or isset($file['internal_tab_'.$i.'_order2'])) {
                $this->hospitalImageUploader($file, 'internal_tab_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_TAB);
            }
        }


        //タブ external
        for($i = 1; $i <= 30; $i++){
            if(isset($file['external_tab_'.$i]) or isset($file['external_tab_'.$i.'_category_id']) or isset($file['external_tab_'.$i.'_memo2']) or isset($file['external_tab_'.$i.'_order2'])) {
                $this->hospitalImageUploader($file, 'external_tab_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_TAB);
            }
        }

        //タブ another
        for($i = 1; $i <= 30; $i++){
            if(isset($file['another_tab_'.$i]) or isset($file['another_tab_'.$i.'_category_id']) or isset($file['another_tab_'.$i.'_memo2']) or isset($file['another_tab_'.$i.'_order2'])) {
                $this->hospitalImageUploader($file, 'another_tab_', $i, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_TAB);
            }
        }

        //インタビュー
        $this->hospitalImageUploader($file, 'interview_', 1, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_INTERVIEW,null,null,null,$file['interview_1_title'],$file['interview_1_caption']);

        //インタビュートップ取得
        $image_category_interview = $this->hospital_category->ByImageOrder($hospital_id, ImageGroupNumber::IMAGE_GROUP_INTERVIEW, 1)->first();
        //interview 詳細　update
        if(isset($file['interview'])) {
            $interviews = $file['interview'];
            foreach ($interviews as $key => $interview) {
                //if(!is_null($interview['answer']) && !is_null($interview['question'])) {
                    $this->interview_detail->where('id', $key)->update($interview);
                //}
            }
        }

        //interview 詳細　insert
        $new_interviews = $file['interview_new'];
        foreach ($new_interviews as $key => $new_interview) {
            if(!is_null($new_interview['answer']) && !is_null($new_interview['question'])) {
                $image_category_interview->interview_details()->saveMany([
                        new InterviewDetail($new_interview)
                    ]
                );
            }
        }

        if(isset($file['map_url'])) {
            $this->hospitalImageUploader($file, 'map_url', 1, $hospital, $hospital_id,ImageGroupNumber::IMAGE_GROUP_MAP);
        }
        return redirect()->route('hospital.image.create', ['hospital_id' => $hospital_id])->with('success', trans('messages.updated', ['name' => trans('messages.names.hospital_categories')]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * hospital_category hospital_image レコード削除
     * @param  int  $hospital_id
     * @param  int  $hospital_category_id
     * @param  int  $hospital_image_id
     * @return \Illuminate\Http\Response
     * todo deleteがdeleteメソッドじゃなくて、getメソッドで削除してるので、直したほうがいいかも。
     */
    public function delete(int $hospital_id, int $hospital_category_id, int $hospital_image_id)
    {
        $hospital_image = $this->hospital_image->find($hospital_image_id);
        $disk = \Storage::disk(env('FILESYSTEM_CLOUD'));
        $disk->delete($hospital_image->name);

        $this->hospital_category->where('id', $hospital_category_id)->delete();
        $this->hospital_image->where('id', $hospital_image_id)->delete();

        return redirect()->route('hospital.image.create', ['hospital_id' => $hospital_id])->with('success', trans('messages.deleted', ['name' => trans('messages.names.hospital_categories')]));
    }

    /**
     * hospital_category hospital_image レコード削除
     * @param  int  $hospital_id
     * @param  int  $interview_id
     * @return \Illuminate\Http\Response
     * todo deleteがdeleteメソッドじゃなくて、getメソッドで削除してるので、直したほうがいいかも。
     */
    public function deleteInterview(int $hospital_id, int $interview_id)
    {
        $this->interview_detail->where('id', $interview_id)->delete();

        return redirect()->route('hospital.image.create', ['hospital_id' => $hospital_id])->with('success', trans('messages.deleted', ['name' => trans('messages.names.hospital_interview')]));
    }

    /**
     * 画像ファイルの削除（レコードの削除はせずに、画像ファイルの削除と画像のパスをNULLにする）
     * @param  int  $hospital_id
     * @param  int  $hospital_category_id
     * @param  int  $hospital_image_id
     * @param  bool $is_sp
     * @return \Illuminate\Http\Response
     * todo deleteメソッドじゃなくて、getメソッド 直したほうがいいかも。
     */
    public function deleteImage(int $hospital_id, int $hospital_image_id)
    {
        $hospital_image = $this->hospital_image->find($hospital_image_id);

        $disk = \Storage::disk(env('FILESYSTEM_CLOUD'));
        $disk->delete($hospital_image->name);
        $this->hospital_category->where('hospital_image_id',$hospital_image_id)->where('image_order',9)->delete();
        $hospital_image->path = null;
        $hospital_image->name = null;
        $hospital_image->save();

        return redirect()->route('hospital.image.create', ['hospital_id' => $hospital_id])->with('success', trans('messages.deleted', ['name' => trans('messages.names.hospital_categories')]));
    }

    /**
     * 画像ファイルの削除（レコードの削除はせずに、画像ファイルの削除と画像のパスをNULLにする）
     * @param  int  $hospital_id
     * @param  int  $hospital_category_id
     * @param  int  $hospital_image_id
     * @param  bool $is_sp
     * @return \Illuminate\Http\Response
     * todo deleteメソッドじゃなくて、getメソッド 直したほうがいいかも。
     */
    public function deleteMainImage(int $hospital_id, int $hospital_image_id, $is_sp)
    {
        $hospital_image = $this->hospital_image->find($hospital_image_id);

        $file_name = str_replace($hospital_id.'/'.$this->base_name, '', $hospital_image->name);

        $disk = \Storage::disk(env('FILESYSTEM_CLOUD'));
        $disk->delete($hospital_image->name);

        $hospital_image->path = null;
        $hospital_image->name = null;
        $hospital_image->save();

        if($is_sp){
        $sp_file_name = $hospital_id.'/'.$this->sp_dir.'/'.$this->base_name.$file_name;
        $sp_img = $this->hospital_image->byImageName($sp_file_name)->first();//sp画像情報取得
        $disk->delete($sp_file_name);
        $sp_img->path = null;
        $sp_img->name = null;
        $sp_img->save();
        }

        return redirect()->route('hospital.image.create', ['hospital_id' => $hospital_id])->with('success', trans('messages.deleted', ['name' => trans('messages.names.hospital_categories')]));
    }

    /**
     * 画像は削除せず、hospital_categoriesのデータだけ削除
     * @param  int  $hospital_id
     * @param  int  $hospital_category_id
     * @param  bool $is_sp
     * @return \Illuminate\Http\Response
     * todo deleteメソッドじゃなくて、getメソッド 直したほうがいいかも。
     */
    public function deleteHospitalCategory(int $hospital_id,int $hospital_category_id)
    {
        $response = $this->hospital_category->destroy($hospital_category_id);
        if( $response ) {
            return response()->json(['status' => '200', 'message' => '削除しました']);
        } else {
            return response()->json(['status' => '500', 'message' => '削除に失敗しました']);
        }
    }

    private function hospitalImageUploader (array $file, string $image_prefix, int $i, object $hospital, int $hospital_id, int $image_order, string $name = null, $career = null, string $memo = null, string $title = null, string $caption = null) {
        //地図も画像情報として保存されるが、画像の実態はないのでダミーで保存するっぽい。
        if ($image_order != ImageGroupNumber::IMAGE_GROUP_MAP) {
            $memo1 = isset($file[$image_prefix.$i.'_memo1']) ? $file[$image_prefix.$i.'_memo1'] : '' ;
            $memo2 = isset($file[$image_prefix.$i.'_memo2']) ? $file[$image_prefix.$i.'_memo2'] : '' ;
            $order2 = isset($file[$image_prefix.$i.'_order2']) ? $file[$image_prefix.$i.'_order2'] : 0 ;
            $name_2 = isset($file[$image_prefix.$i.'_name']) ? $file[$image_prefix.$i.'_name'] : '' ;

            $location_no = isset($file[$image_prefix.$i.'_location']) ? $file[$image_prefix.$i.'_location'] : null ;
            //画像の登録確認
            //tab画像だけはタブのカテゴリ$file_locationもチェックする
            if($image_order == ImageGroupNumber::IMAGE_GROUP_TAB) {
                $image_order_exists = $this->hospital_category->ByImageOrderAndFileLocationNo($hospital_id, $image_order, $i, $location_no)->first();
            } else {
                $image_order_exists = $this->hospital_category->ByImageOrder($hospital_id, $image_order, $i)->first();
            }

            if(isset($file[$image_prefix.$i])) {
             $extension = $file[$image_prefix.$i]->getClientOriginalExtension();
            //pc保存 putFile メソッドでuniqueファイル名を返す
            $img_info =$this->putFileStorageImage($file[$image_prefix.$i],$hospital_id);
            $save_sub_images = ['extension' => $extension, 'name' => $img_info['pc_img_name'], 'path' => $img_info['pc_img_url'], 'memo1' => $memo1, 'memo2' => $memo2];
            $save_sub_image_categories = [ 'title' => $title,'caption' => $caption, 'name' => $name_2,'career' => $career,  'memo' => $memo, 'hospital_id' => $hospital_id, 'image_order' => $image_order, 'order' => $i, 'order2' => $order2, 'file_location_no' => $location_no];
            } else {
                $save_sub_images = ['memo1' => $memo1, 'memo2' => $memo2];
                $save_sub_image_categories = [ 'title' => $title,'caption' => $caption, 'name' => $name_2,'career' => $career,  'memo' => $memo, 'hospital_id' => $hospital_id, 'image_order' => $image_order, 'order' => $i, 'order2' => $order2 , 'file_location_no' => $location_no];
            }

            if(is_null($image_order_exists)) {
                $hospital->hospital_images()->saveMany([
                        $hospital_img = new HospitalImage($save_sub_images)
                    ]
                );
                return $hospital_img->hospital_category()->create($save_sub_image_categories);
            } else {
                $hospital_img = $hospital->hospital_images()->find($image_order_exists->hospital_image_id);
                $hospital_img->update($save_sub_images);
                $hospital_img->hospital_category()->where('is_display', 0)
                    ->update($save_sub_image_categories);
            }
        } else {
            $save_sub_images = ['extension' => 'dummy', 'name' => 'dummy', 'path' => 'dummy', 'memo1' => $file['map_url']];
            $save_sub_image_categories = [ 'title' => $title,'caption' => $caption, 'hospital_id' => $hospital_id, 'image_order' => $image_order, 'order2' => $i ];
            //メイン画像の登録確認
            $image_category = $this->hospital_category->ByImageOrder($hospital_id, $image_order, $i)->first();
            if(is_null($image_category)) {
                $hospital->hospital_images()->saveMany([
                        $hospital_img = new HospitalImage($save_sub_images)
                    ]
                );
                $hospital_img->hospital_category()->create($save_sub_image_categories);
            } else {
                $hospital_img = $image_category->hospital_image()->first();
                $hospital_img->update($save_sub_images);
                $hospital_img->hospital_category()->update($save_sub_image_categories);
            }
        }
    }
    /**
     * 画像ファイルをアップロード
     * @param  File  $file
     * @param  int  $hospital_id
     * @param  bool  $is_sp
     * @return \Illuminate\Http\Response
     */
    private function putFileStorageImage ($file,$hospital_id,$is_sp = false) {
        //pc画像を保存 putFile メソッドでuniqueファイル名を返す
        $image_path = \Storage::disk(env('FILESYSTEM_CLOUD'))->putFile($hospital_id.'/'.$this->base_name, $file, 'public');
        $img_url = \Storage::disk(env('FILESYSTEM_CLOUD'))->url($image_path);
        $file_name = str_replace($hospital_id.'/'.$this->base_name, '', $image_path);
        $img_info = [
            'pc_img_url' => $img_url,
            'pc_img_name' => $image_path,
        ];

        if($is_sp){
            // スマホ用画像を横幅アスペクト比維持の自動サイズへリサイズ
            $sp_image = \Image::make($file)
                ->resize(HospitalImage::SP_IMAGE_WIDTH, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            //sp画像はputでファイル名指定
            \Storage::disk(env('FILESYSTEM_CLOUD'))->put($hospital_id.'/'.$this->sp_dir.'/'.$this->base_name.$file_name, (string) $sp_image->encode(), 'public');
            $img_url_sp = \Storage::url($hospital_id.'/'.$this->sp_dir.'/'.$this->base_name.$file_name);
            $img_info['sp_img_url'] =  $img_url_sp;
            $img_info['sp_img_name'] =  $hospital_id.'/'.$this->sp_dir.'/'.$this->base_name.$file_name;
        }
        return $img_info;
    }
    private function saveImageAndDeleteOldImage ($hospital,$image_category,$save_images,$save_image_categories) {
        if(is_null($image_category)) {
            $hospital->hospital_images()->saveMany([
                    $hospital_img = new HospitalImage($save_images),
                ]
            );
            $hospital_img->hospital_category()->create($save_image_categories);
        } else {
            $hospital_img = $hospital->hospital_images()->find($image_category->hospital_image_id);

            //古いファイルを削除
            $disk = \Storage::disk(env('FILESYSTEM_CLOUD'));
            $disk->delete($hospital_img->name);

            $hospital_img->update($save_images);
            $hospital_img->hospital_category()->update($save_image_categories);
        }
    }
    private function isLockVersionTrue (string $model_name, object $hospital, $request_lock_version) {
        $lock = $this->lock->byHospitalIdAndModel($model_name,$hospital->id)->first();
        if(!is_null($lock)) {
            if($hospital->lock->lock_version != $request_lock_version) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }
}
