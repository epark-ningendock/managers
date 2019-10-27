<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ImagePathsResource extends Resource
{
    /**
     * 画像ファイルパス リソースクラス
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!isset($this)) return;

        $path = $this->path ?? '';
//        $name = $this->name ?? '';
//        $extension = $this->extension ?? '';
        $alt = $this->memo1 ?? '';

        return
        [
            'url' => $path ,
            'alt' => $alt,
        ];
    }
}
