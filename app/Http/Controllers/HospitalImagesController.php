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
        HospitalCategory $hospital_category
    )
    {
        $this->hospital_image = $hospital_image;
        $this->hospital_category = $hospital_category;
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

        return view('hospital_images.create', compact('hospital_id'));
        return view('hospital_images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $hospital_id)
    {
        $params = $request->validate([
            'main' => 'file|image|max:4000',
            'sub.*' => 'file|image|max:4000',
        ]);
        $file = $params;

        $save_images = [];

        //main
        $image = \Image::make(file_get_contents($file['main']->getRealPath()));
        $image
            ->save(public_path().'/img/uploads/'.$file['main']->hashName())
            ->resize(300, 300)
            ->save(public_path().'/img/uploads/300-300-'.$file['main']->hashName())
            ->resize(500, 500)
            ->save(public_path().'/img/uploads/500-500-'.$file['main']->hashName());
        $save_images[] = ['extension' => str_replace('image/', '', $image->mime), 'name' => $file['main']->hashName(), 'path' => $file['main']->hashName()];
        //$save_image_categories[] = [ 'order' => 1, 'order2' => HospitalCategory::FACILITY, 'is_display' => HospitalCategory::SHOW];

        //sub
        foreach($file['sub'] as $key => $sub){
            $sub_image = \Image::make(file_get_contents($sub->getRealPath()));
            $sub_image
                ->save(public_path().'/img/uploads/'.$sub->hashName())
                ->resize(100, 100)
                ->save(public_path().'/img/uploads/300-300-'.$sub->hashName())
                ->resize(200, 200)
                ->save(public_path().'/img/uploads/500-500-'.$sub->hashName());
            $save_images[] = ['extension' => str_replace('image/', '', $sub_image->mime), 'name' => $sub->hashName(), 'path' => $sub->hashName()];
            $save_image_categories[] = [ 'order' => $key, 'order2' => HospitalCategory::FACILITY, 'is_display' => HospitalCategory::SHOW];
        }

        $hospital = Hospital::find($hospital_id);

        foreach($save_images as $key => $img ){
            $hospital->hospital_images()->saveMany([
                    $hospital_img = new HospitalImage($img)
                ]
            );
            $save_image_categories[$key]['hospital_id'] = $hospital_id;
            $hospital_img->hospital_category()->create($save_image_categories[$key]);
        }
        return redirect('/img/uploads/'.$file['main']->hashName());
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
