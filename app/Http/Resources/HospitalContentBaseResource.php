<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Log;

class HospitalContentBaseResource extends JsonResource
{
    /**
     * 医療機関コンテンツ into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections()->toArray();
    }

    /**
     * 医療機関コンテンツ
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        $main_image_pc = collect(['img_main_url' => '', 'img_main_alt' => '',]);
        $main_image_sp = collect(['img_main_sp_url' => '', 'img_main_sp_alt' => '',]);
        $top_title = collect(['title' => '', 'caption' => '',]);
        $photo = collect(['photo' => '', 'photo_desc' => '',]);
        $hospital_movie = collect(['access_movie_url' => '',]);

        return collect([])
            ->merge($this->_main_image($this->hospital_categories, 1) ?? $main_image_pc)
            ->merge($this->_main_image($this->hospital_categories, 2) ?? $main_image_sp)
            ->put('img_sub', $this->_sub_images($this->hospital_categories))
            ->merge($this->_top_title($this->hospital_categories) ?? $top_title)
            ->put('point', $this->_recommend($this->hospital_categories))
            ->put('hospital_photo', $this->_hospital_photo($this->hospital_categories))
            ->merge($this->_photo($this->hospital_categories) ?? $photo)
            ->merge($this->_hospital_movie($this->hospital_categories) ?? $hospital_movie)
            ->put('interview', $this->_interview($this->hospital_categories))
            ->put('staff', $this->_staff($this->hospital_categories))
            ->put('free_area', $this->free_area ?? '')
            ->put('principal', $this->principal ?? '')
            ->put('principal_bio', $this->principal_history ?? '')
            ->put('category', HospitalCategoryResource::collection($this->hospital_details));
    }

    /**
     * 医療施設メイン画像取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _main_image($hospital_categories, $image_location_number)
    {
        $categories = $hospital_categories->filter(function ($c) use ($image_location_number) {
            return isset($c->image_order) && $c->image_order->image_location_number === $image_location_number;
        });

        $images = $categories->map(function ($i) use ($image_location_number) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            return $image_location_number === 1 ?
                collect(['img_main_url' => $url, 'img_main_alt' => $alt,])
                : collect(['img_main_sp_url' => $url, 'img_main_sp_alt' => $alt,]);
        });
        return $images->first();
    }

    /**
     * 医療施設メイン画像取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _sub_images($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order) && $c->image_order->image_location_number === 2;
        });

        $images = $categories->map(function ($i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            return ['img_sub_url' => $url, 'img_sub_alt' => $alt,];
        });
        return $images;
    }
    /**
     * TOP（タイトル）/本文取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _top_title($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order)
                && $c->image_order->image_group_number === 3
                && $c->image_order->image_location_number === 1;
        });

        $texts = $categories->map(function ($t) {
            $title = $t->title ?? '';
            $caption = $t->caption ?? '';
            return collect(['title' => $title, 'caption' => $caption,]);
        });
        return $texts->first();
    }

    /**
     * こだわり条件取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _recommend($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order) && $c->image_order->image_group_number === 5;
        });

        $images = $categories->map(function ($i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            $title = $i->title ?? '';
            $desc = $i->caption ?? '';
            $img_pos = $i->image_order->image_location_number ?? '';
            return [
                'img_url' => $url,
                'img_alt' => $alt,
                'title' => $title,
                'desc' => $desc,
                'img_pos' => $img_pos,
            ];
        });
        return $images;
    }

    /**
     * 施設写真取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _hospital_photo($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order) && $c->image_order->image_group_number === 8;
        });

        $images = $categories->map(function ($i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            $desc = $i->caption ?? '';
            $img_pos = $i->image_order->image_location_number ?? '';
            return [
                'img_url' => $url,
                'img_alt' => $alt,
                'desc' => $desc,
                'type' => $img_pos,
            ];
        });
        return $images;
    }

    /**
     * 写真取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _photo($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order) && $c->image_order->image_group_number === 8;
        });

        $images = $categories->map(function ($t) {
            $img_url = '';
            $img_alt = '';
            if ($t->hospital_image->is_display === 1) { // 写真
                $img_url = $this->_filepath($t->hospital_image);
                if ($t->order === 1) { // 写真説明
                    $img_alt = $t->caption ?? '';
                }
            }
            return collect(['photo' => $img_url, 'photo_desc' => $img_alt,]);
        });
        return $images->first();
    }

    /**
     * アクセス動画URL
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _hospital_movie($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order)
                && $c->image_order->image_group_number === 4
                && $c->image_order->image_location_number === 1;
        });

        $texts = $categories->map(function ($t) {
            $url = $t->hospital_image->memo1 ?? '';
            return collect(['access_movie_url' => $url,]);
        });
        return $texts->first();
    }

    /**
     * インタビュー
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _interview($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order) && $c->image_order->image_group_number === 6;
        });

        $texts = $categories->map(function ($i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            $title = $i->title ?? '';
            $desc = $i->caption ?? '';
            $contents = $i->interview_details->map(function ($i) {
                return $i->answer ?? '';
            });
            return [
                'img_url' => $url,
                'img_alt' => $alt,
                'title' => $title ?? '',
                'desc' => $desc ?? '',
                'contents' => $contents,
            ];
        });
        return $texts;
    }

    /**
     * スタッフ
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _staff($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order) && $c->image_order->image_group_number === 7;
        });
        $texts = $categories->map(function ($i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            $name = $i->name ?? '';
            $bio = $i->career ?? '';
            $comment = $i->memo ?? '';
            return [
                'img_url' => $url,
                'img_alt' => $alt,
                'name' => $name,
                'bio' => $bio,
                'comment' => $comment
            ];
        });
        return $texts;
    }

    private function _filepath($hospital_image)
    {
        if (!isset($hospital_image)) return '';
        $path = $hospital_image->path ?? '';
        $name = $hospital_image->name ?? '';
        $extension = $hospital_image->extension ?? '';
        return $path === '' || $extension === '' || $name === '' ? '' : WWW_SITE . $path . $name . $extension;
    }
}
