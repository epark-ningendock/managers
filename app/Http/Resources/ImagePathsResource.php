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
        if(!isset($this)) return [];

        $path = $this->path ?? '';
        $alt = '';

        return
        [
            'url' => $path ,
            'alt' => $alt,
        ];
    }
}
