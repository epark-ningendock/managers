<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\HospitalImage;
use App\ImageOrder;
use App\HospitalCategory;
use Illuminate\Http\Request;

class HospitalImagesController extends Controller
{
    public function __construct(
        HospitalImage $hospital_image,
        HospitalCategory $hospital_category,
        ImageOrder $image_order

    )
    {
        $this->hospital_image = $hospital_image;
        $this->hospital_category = $hospital_category;
        $this->image_order = $image_order;
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

        $image_order = $this->image_order;

        return view('hospital_images.create', compact('hospital', 'hospital_id', 'image_order'));
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
            'speciality_1' => 'file|image|max:4000',
            'speciality_2' => 'file|image|max:4000',
            'speciality_3' => 'file|image|max:4000',
            'speciality_4' => 'file|image|max:4000',
            'title' => 'nullable|max:100',
            'caption' => 'nullable|max:200',
            'map_url' => 'nullable|max:200',
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
        for($i = 1; $i <= 4; $i++){
            if(isset($file['speciality_'.$i])) {
                $this->hospitalImageUploader($file, 'speciality_', $i, $hospital, $hospital_id,ImageOrder::IMAGE_GROUP_SPECIALITY);
            }
        }
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

    private function hospitalImageUploader (array $file, string $image_prefix, int $i, object $hospital, int $hospital_id, int $image_order) {
        //地図も画像情報として保存されるが、画像の実態はないのでダミーで保存するっぽい。
        if ($image_order != ImageOrder::IMAGE_GROUP_MAP) {
            $sub_image = \Image::make(file_get_contents($file[$image_prefix.$i]->getRealPath()));
            $sub_image
                ->save(public_path().'/img/uploads/'.$file[$image_prefix.$i]->hashName())
                ->resize(300, 300)
                ->save(public_path().'/img/uploads/300-300-'.$file[$image_prefix.$i]->hashName())
                ->resize(500, 500)
                ->save(public_path().'/img/uploads/500-500-'.$file[$image_prefix.$i]->hashName());

            $save_sub_images = ['extension' => str_replace('image/', '', $sub_image->mime), 'name' => $file[$image_prefix.$i]->getClientOriginalName(), 'path' => $file[$image_prefix.$i]->hashName()];
            $save_sub_image_categories = [ 'hospital_id' => $hospital_id, 'image_order' => $image_order, 'order2' => $i ];
            //メイン画像の登録確認
            $image_category = $this->hospital_category->ByImageOrder($hospital_id, $image_order, $i)->first();
            if(is_null($image_category)) {
                $hospital->hospital_images()->saveMany([
                        $hospital_img = new HospitalImage($save_sub_images)
                    ]
                );
                $hospital_img->hospital_category()->create($save_sub_image_categories);
            } else {
                $hospital_img = $hospital->hospital_images()->find($image_category->hospital_image_id);
                $hospital_img->update($save_sub_images);
                $hospital_img->hospital_category()->update($save_sub_image_categories);
            }
        } else {
            $save_sub_images = ['extension' => 'dummy', 'name' => 'dummy', 'path' => 'dummy', 'memo1' => $file['map_url']];
            $save_sub_image_categories = [ 'hospital_id' => $hospital_id, 'image_order' => $image_order, 'order2' => $i ];
            //メイン画像の登録確認
            $image_category = $this->hospital_category->ByImageOrder($hospital_id, $image_order, $i)->first();
            if(is_null($image_category)) {
                $hospital->hospital_images()->saveMany([
                        $hospital_img = new HospitalImage($save_sub_images)
                    ]
                );
                $hospital_img->hospital_category()->create($save_sub_image_categories);
            } else {
               // dd($image_category);
                $hospital_img = $image_category->hospital_image()->first();
                $hospital_img->update($save_sub_images);
                $hospital_img->hospital_category()->update($save_sub_image_categories);
            }
        }

    }
}
