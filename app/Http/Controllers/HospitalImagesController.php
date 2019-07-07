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
        ]);
        $file = $params;

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
                //dd($save_image_categories);
                $hospital_img->hospital_category()->update($save_image_categories);
            }
        }
        //sub
        for($i = 1; $i <= 4; $i++){
            if(isset($file['sub_'.$i])) {
                $sub_image = \Image::make(file_get_contents($file['sub_'.$i]->getRealPath()));
                $sub_image
                    ->save(public_path().'/img/uploads/'.$file['sub_'.$i]->hashName())
                    ->resize(100, 100)
                    ->save(public_path().'/img/uploads/300-300-'.$file['sub_'.$i]->hashName())
                    ->resize(200, 200)
                    ->save(public_path().'/img/uploads/500-500-'.$file['sub_'.$i]->hashName());

                $save_sub_images = ['extension' => str_replace('image/', '', $sub_image->mime), 'name' => $file['sub_'.$i]->getClientOriginalName(), 'path' => $file['sub_'.$i]->hashName()];
                $save_sub_image_categories = [ 'hospital_id' => $hospital_id, 'image_order' => ImageOrder::IMAGE_GROUP_FACILITY_SUB, 'order2' => $i ];


            //メイン画像の登録確認
            $image_category = $this->hospital_category->ByImageOrder($hospital_id, ImageOrder::IMAGE_GROUP_FACILITY_SUB, $i)->first();

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
            }
        }
        return redirect()->route('hospital.image.create', ['hospital_id' => $hospital_id])->with('success', trans('画像の更新が完了しました。'));
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
}
