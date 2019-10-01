<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImagePathsResource extends JsonResource
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
        $name = $this->name ?? '';
        $extension = $this->extension ?? '';
        $alt = $this->memo1 ?? '';

        return
        [
            'url' => $path === '' || $extension === '' || $name === '' ? '' : WWW_SITE . $path . $name . $extension,
            'alt' => $alt,
        ];
    }
}
