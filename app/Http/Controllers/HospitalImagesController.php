<?php

namespace App\Http\Controllers;

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
    public function store(Request $request, $hosital_id)
    {
        $params = $request->validate([
            'main_image' => 'file|image|max:4000',
        ]);

        $file = $params['main_image'];

        $image = \Image::make(file_get_contents($file->getRealPath()));
        $image
            ->save(public_path().'/img/uploads/'.$file->hashName())
            ->resize(300, 300)
            ->save(public_path().'/img/uploads/300-300-'.$file->hashName())
            ->resize(500, 500)
            ->save(public_path().'/img/uploads/500-500-'.$file->hashName());
        dd(public_path().'/img/uploads/'.$file->hashName());
        return redirect('/img/uploads/'.$file->hashName());
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
