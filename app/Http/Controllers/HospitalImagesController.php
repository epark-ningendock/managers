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
            'image.*.' => 'file|image|max:4000',
        ]);

        $file = $params;

        $image = \Image::make(file_get_contents($file['image']['main']->getRealPath()));
        $image
            ->save(public_path().'/img/uploads/'.$file['image']['main']->hashName())
            ->resize(300, 300)
            ->save(public_path().'/img/uploads/300-300-'.$file['image']['main']->hashName())
            ->resize(500, 500)
            ->save(public_path().'/img/uploads/500-500-'.$file['image']['main']->hashName());

        $hospital = Hospital::find($hospital_id);

        $hospital->hospital_images()->saveMany([
            new HospitalImage(['extension' => 'png', 'name' => $file['image']['main']->hashName(), 'path' => $file['image']['main']->hashName(),'category' => 'main']),
        ]);
        return redirect('/img/uploads/'.$file['image']['main']->hashName());
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
