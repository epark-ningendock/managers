<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\HospitalImage;
use App\ImageOrder;
use App\HospitalCategory;
use App\InterviewDetail;
use Illuminate\Http\Request;

class HospitalImagesController extends Controller
{
    public function __construct(
        HospitalImage $hospital_image,
        HospitalCategory $hospital_category,
        ImageOrder $image_order,
        InterviewDetail $interview_detail
    )
    {
        $this->hospital_image = $hospital_image;
        $this->hospital_category = $hospital_category;
        $this->image_order = $image_order;
        $this->interview_detail = $interview_detail;
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
        $hospital = Hospital::with(['hospital_images', 'hospital_categories'])->find($hospital_id);
        $interviews = $hospital->hospital_categories()->where('image_order', ImageOrder::IMAGE_GROUP_INTERVIEW)->first()->interview_details()->interviewOrder()->get();

        $image_order = $this->image_order;

        $tab_name_list = [ 1 => 'スタッフ',  2 => '設備',  3 => '院内' , 4 => '外観',  5 => 'その他'];

        return view('hospital_images.create', compact('hospital', 'hospital_id', 'image_order', 'tab_name_list', 'interviews'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $hospital_id)
    {
        $hospital = Hospital::find($hospital_id);

        $params = $request->validate([
            'main' => 'file|image|max:4000',
            'sub_1' => 'file|image|max:4000',
            'sub_2' => 'file|image|max:4000',
            'sub_3' => 'file|image|max:4000',
            'sub_4' => 'file|image|max:4000',
            'sub_5' => 'file|image|max:4000',
            'speciality_1' => 'file|image|max:4000',
            'speciality_2' => 'file|image|max:4000',
            'speciality_3' => 'file|image|max:4000',
            'speciality_4' => 'file|image|max:4000',
            'title' => 'nullable|max:100',
            'caption' => 'nullable|max:200',
            'map_url' => 'nullable|max:200',

            'tab_1' => 'file|image|max:4000',
            'tab_1_order1' => 'nullable|max:99|numeric',
            'tab_1_memo1' => 'nullable|max:200',
            'tab_1_memo2' => 'nullable|max:200',

            'tab_2' => 'file|image|max:4000',
            'tab_2_order1' => 'nullable|max:99|numeric',
            'tab_2_memo1' => 'nullable|max:200',
            'tab_2_memo2' => 'nullable|max:200',

            'tab_3' => 'file|image|max:4000',
            'tab_3_order1' => 'nullable|max:99|numeric',
            'tab_3_memo1' => 'nullable|max:200',
            'tab_3_memo2' => 'nullable|max:200',

            'tab_4' => 'file|image|max:4000',
            'tab_4_order1' => 'nullable|max:99|numeric',
            'tab_4_memo1' => 'nullable|max:200',
            'tab_4_memo2' => 'nullable|max:200',

            'tab_5' => 'file|image|max:4000',
            'tab_5_order1' => 'nullable|max:99|numeric',
            'tab_5_memo1' => 'nullable|max:200',
            'tab_5_memo2' => 'nullable|max:200',

            'staff_' => 'file|image|max:4000',
            'staff_1_name' => 'nullable|max:100',
            'staff_1_career' => 'nullable|max:300',
            'staff_1_memo' => 'nullable',
            'staff_1_memo1' => 'nullable|max:200',

            'staff_2' => 'file|image|max:4000',
            'staff_2_name' => 'nullable|max:100',
            'staff_2_career' => 'nullable|max:300',
            'staff_2_memo' => 'nullable',
            'staff_2_memo1' => 'nullable|max:200',

            'staff_3' => 'file|image|max:4000',
            'staff_3_name' => 'nullable|max:100',
            'staff_3_career' => 'nullable|max:300',
            'staff_3_memo' => 'nullable',
            'staff_3_memo1' => 'nullable|max:200',

            'staff_4' => 'file|image|max:4000',
            'staff_4_name' => 'nullable|max:100',
            'staff_4_career' => 'nullable|max:300',
            'staff_4_memo' => 'nullable',
            'staff_4_memo1' => 'nullable|max:200',

            'staff_5' => 'file|image|max:4000',
            'staff_5_name' => 'nullable|max:100',
            'staff_5_career' => 'nullable|max:300',
            'staff_5_memo' => 'nullable',
            'staff_5_memo1' => 'nullable|max:200',

            'staff_6' => 'file|image|max:4000',
            'staff_6_name' => 'nullable|max:100',
            'staff_6_career' => 'nullable|max:300',
            'staff_6_memo' => 'nullable',
            'staff_6_memo1' => 'nullable|max:200',

            'staff_7' => 'file|image|max:4000',
            'staff_7_name' => 'nullable|max:100',
            'staff_7_career' => 'nullable|max:300',
            'staff_7_memo' => 'nullable',
            'staff_7_memo1' => 'nullable|max:200',

            'staff_8' => 'file|image|max:4000',
            'staff_8_name' => 'nullable|max:100',
            'staff_8_career' => 'nullable|max:300',
            'staff_8_memo' => 'nullable',
            'staff_8_memo1' => 'nullable|max:200',

            'staff_9' => 'file|image|max:4000',
            'staff_9_name' => 'nullable|max:100',
            'staff_9_career' => 'nullable|max:300',
            'staff_9_memo' => 'nullable',
            'staff_9_memo1' => 'nullable|max:200',

            'staff_10' => 'file|image|max:4000',
            'staff_10_name' => 'nullable|max:100',
            'staff_10_career' => 'nullable|max:300',
            'staff_10_memo' => 'nullable',
            'staff_10_memo1' => 'nullable|max:200',

            'interview_1' => 'file|image|max:4000',
            'interview_1_title' => 'nullable|max:100',
            'interview_1_caption' => 'nullable|max:200',

            'interview.*.question' => 'nullable|max:100',
            'interview.*.answer' => 'nullable|max:100',
        ]);
        $file = $params;

        //TOPの保存
        $hospital->hospital_categories()->updateOrCreate(
            ['hospital_id' => $hospital_id,'image_order' => ImageOrder::IMAGE_GROUP_TOP],
            [
                'hospital_id' => $hospital_id,
                'image_order' => ImageOrder::IMAGE_GROUP_TOP,
                'title' => $params['title'],
                'caption' => $params['caption'],
                'order2' => 1
            ]
        );

        $interviews = $params['interview'];
        foreach ($interviews as $key => $interview) {
            $this->interview_detail->where('id', $key)->update($interview);
        }

        //main画像の保存
        if(isset($file['main'])) {
            $image = \Image::make(file_get_contents($file['main']->getRealPath()));
            $image
                ->save(public_path().'/img/uploads/'.$file['main']->hashName())
                ->resize(300, 300)
                ->save(public_path().'/img/uploads/300-300-'.$file['main']->hashName())
                ->resize(500, 500)
                ->save(public_path().'/img/uploads/500-500-'.$file['main']->hashName());

            //HospitalImage HospitalCategory 保存用array
            $save_images = ['extension' => str_replace('image/', '', $image->mime), 'name' => $file['main']->getClientOriginalName(), 'path' => $file['main']->hashName()];
            $save_image_categories = [ 'hospital_id' => $hospital_id, 'image_order' => ImageOrder::IMAGE_GROUP_FACILITY_MAIN ];

            //メイン画像の登録確認
            $image_category = $this->hospital_category->ByImageOrder($hospital_id, ImageOrder::IMAGE_GROUP_FACILITY_MAIN, 0)->first();

            if(is_null($image_category)) {
                $hospital->hospital_images()->saveMany([
                        $hospital_img = new HospitalImage($save_images)
                    ]
                );
                $hospital_img->hospital_category()->create($save_image_categories);
            } else {
                $hospital_img = $hospital->hospital_images()->find($image_category->hospital_image_id);
                $hospital_img->update($save_images);
                $hospital_img->hospital_category()->update($save_image_categories);
            }
        }
        //sub
        for($i = 1; $i <= 4; $i++){
            if(isset($file['sub_'.$i])) {
                $this->hospitalImageUploader($file, 'sub_', $i, $hospital, $hospital_id,ImageOrder::IMAGE_GROUP_FACILITY_SUB);
            }
        }
        //こだわり
        for($i = 1; $i <= 4; $i++){
            if(isset($file['speciality_'.$i])) {
                $this->hospitalImageUploader($file, 'speciality_', $i, $hospital, $hospital_id,ImageOrder::IMAGE_GROUP_SPECIALITY);
            }
        }
        //タブ
        for($i = 1; $i <= 5; $i++){
            $this->hospitalImageUploader($file, 'tab_', $i, $hospital, $hospital_id,ImageOrder::IMAGE_GROUP_TAB);
        }
        //スタッフ
        for($i = 1; $i <= 10; $i++){
            $this->hospitalImageUploader($file, 'staff_', $i, $hospital, $hospital_id,ImageOrder::IMAGE_GROUP_STAFF,$file['staff_'.$i.'_name'],$file['staff_'.$i.'_career'],$file['staff_'.$i.'_memo'] );
        }

        //インタビュー
        $this->hospitalImageUploader($file, 'interview_', 1, $hospital, $hospital_id,ImageOrder::IMAGE_GROUP_INTERVIEW,null,null,null,$file['interview_1_title'],$file['interview_1_caption']);

        if(isset($file['map_url'])) {
            $this->hospitalImageUploader($file, 'map_url', 1, $hospital, $hospital_id,ImageOrder::IMAGE_GROUP_MAP);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function hospitalImageUploader (array $file, string $image_prefix, int $i, object $hospital, int $hospital_id, int $image_order, string $name = null, $career = null, string $memo = null, string $title = null, string $caption = null) {
        //地図も画像情報として保存されるが、画像の実態はないのでダミーで保存するっぽい。
        if ($image_order != ImageOrder::IMAGE_GROUP_MAP) {

            $memo1 = isset($file[$image_prefix.$i.'_memo1']) ? $file[$image_prefix.$i.'_memo1'] : '' ;
            $memo2 = isset($file[$image_prefix.$i.'_memo2']) ? $file[$image_prefix.$i.'_memo2'] : '' ;
            $order = isset($file[$image_prefix.$i.'_order1']) ? $file[$image_prefix.$i.'_order1'] : 0 ;

            //メイン画像の登録確認
            $image_order_exists = $this->hospital_category->ByImageOrder($hospital_id, $image_order, $i)->first();

            if(isset($file[$image_prefix.$i])) {
            $sub_image = \Image::make(file_get_contents($file[$image_prefix.$i]->getRealPath()));
            $sub_image
                ->save(public_path().'/img/uploads/'.$file[$image_prefix.$i]->hashName())
                ->resize(300, 300)
                ->save(public_path().'/img/uploads/300-300-'.$file[$image_prefix.$i]->hashName())
                ->resize(500, 500)
                ->save(public_path().'/img/uploads/500-500-'.$file[$image_prefix.$i]->hashName());
            $save_sub_images = ['extension' => str_replace('image/', '', $sub_image->mime), 'name' => $file[$image_prefix.$i]->getClientOriginalName(), 'path' => $file[$image_prefix.$i]->hashName(), 'memo1' => $memo1, 'memo2' => $memo2];
            $save_sub_image_categories = [ 'title' => $title,'caption' => $caption, 'name' => $name,'career' => $career,  'memo' => $memo, 'hospital_id' => $hospital_id, 'image_order' => $image_order, 'order2' => $i, 'order' => $order ];
            } else {
                $save_sub_images = ['memo1' => $memo1, 'memo2' => $memo2];
                $save_sub_image_categories = [ 'title' => $title,'caption' => $caption, 'name' => $name,'career' => $career,  'memo' => $memo, 'hospital_id' => $hospital_id, 'image_order' => $image_order, 'order2' => $i, 'order' => $order ];
            }

            if(is_null($image_order_exists)) {
                $hospital->hospital_images()->saveMany([
                        $hospital_img = new HospitalImage($save_sub_images)
                    ]
                );
                $hospital_img->hospital_category()->create($save_sub_image_categories);
            } else {

                $hospital_img = $hospital->hospital_images()->find($image_order_exists->hospital_image_id);
                $hospital_img->update($save_sub_images);
                $hospital_img->hospital_category()->update($save_sub_image_categories);
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

    private function save_interview_details(){

    }
}
