<?php

namespace App;

use App\Enums\CourseImageType;

class CourseImage extends SoftDeleteModel
{
    protected $fillable = [ 'course_id', 'name', 'extension', 'path', 'type' ];

    protected $enums = ['type' => CourseImageType::class];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function name_for_upload(String $name)
    {
        return str_replace('App\\', '', get_class($this)).'/'.strval($this->id).'/'.$name;
    }

    public function hospital_image()
    {
        return $this->belongsTo('App\HospitalImage');
    }

    public function image_order()
    {
        return $this->belongsTo('App\ImageOrder');
    }
}
