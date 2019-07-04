<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\HospitalImage;
use Illuminate\Http\Request;

class HospitalImagesController extends Controller
{
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
        //save用のArray

        $save_images[] = ['extension' => str_replace('image/', '', $image->mime), 'name' => $file['main']->hashName(), 'path' => $file['main']->hashName(),'category' => 'main'];

        //sub
        foreach($file['sub'] as $key => $sub){
            $sub_image = \Image::make(file_get_contents($sub->getRealPath()));
            $sub_image
                ->save(public_path().'/img/uploads/'.$sub->hashName())
                ->resize(100, 100)
                ->save(public_path().'/img/uploads/300-300-'.$sub->hashName())
                ->resize(200, 200)
                ->save(public_path().'/img/uploads/500-500-'.$sub->hashName());
            $save_images[] = ['extension' => str_replace('image/', '', $sub_image->mime), 'name' => $sub->hashName(), 'path' => $sub->hashName(),'category' => 'sub','sort' => $key];
        }

        $hospital = Hospital::find($hospital_id);

        foreach($save_images as $img ){
            $hospiますtal->hospital_images()->saveMany([
                new HospitalImage($img),
            ]);

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
