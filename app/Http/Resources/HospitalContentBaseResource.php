<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Log;

class HospitalContentBaseResource extends Resource
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
        $staff = $this->_staff($this->hospital_categories);

        return collect([])
            ->merge($this->_main_image($this->hospital_categories, 1, 1) ?? $main_image_pc)
            ->merge($this->_main_image($this->hospital_categories, 1, 2) ?? $main_image_sp)
            ->put('img_sub', $this->_sub_images($this->hospital_categories))
            ->merge($this->_top_title($this->hospital_categories) ?? $top_title)
            ->put('point', $this->_recommend($this->hospital_categories))
            ->put('hospital_photo', $this->_hospital_photo($this->hospital_categories))
            ->merge($this->_photo($this->hospital_categories) ?? $photo)
            ->merge($this->_hospital_movie($this->hospital_categories) ?? $hospital_movie)
            ->put('interview', $this->_interview($this->hospital_categories))
            ->put('staff_img_url', $staff[0])
            ->put('staff_img_alt', $staff[1])
            ->put('staff_name', $staff[2])
            ->put('staff_comment', $staff[3])
            ->put('staff_bio', $staff[4])
            ->put('free_area', $this->free_area ?? '')
            ->put('principal', $this->principal ?? '')
            ->put('principal_bio', $this->principal_history ?? '')
            ->put('category', new HospitalCategoryResource($this));
    }

    /**
     * 医療施設メイン画像取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _main_image($hospital_categories, $image_group_number, $image_location_number)
    {
        $categories = $hospital_categories->filter(function ($c) use ($image_group_number, $image_location_number) {
            return (isset($c->image_order) && $c->image_order == $image_group_number)
                && (isset($c->file_location_no) &&  $c->file_location_no == $image_location_number);
        });

        $images = $categories->map(function ($i) use ($image_group_number, $image_location_number) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';

            return ($i->image_order === $image_group_number && $i->file_location_no == $image_location_number && $image_location_number == 1) ?
                collect(['img_main_url' => $url, 'img_main_alt' => $alt,])
                : collect(['img_main_sp_url' => $url, 'img_main_sp_alt' => $alt,]);
        });

        if (empty($images) && $image_location_number == 1) {
            return ['img_main_url' => '', 'img_main_alt' => ''];
        } elseif (empty($images) && $image_location_number == 2) {
            return ['img_main_sp_url' => '', 'img_main_sp_alt' => ''];
        }
        return $images->first();
    }

    /**
     * 医療施設メイン画像取得
     * 
     * @param  医療機関カテゴリ
     * @return 医療施設サブ
     */
    private function _sub_images($hospital_categories)
    {
        $categories = $hospital_categories->filter(function ($c) {
            return isset($c->image_order)
                && $c->image_order == 2;
        });

        $results = [];
        foreach ($categories as $category) {
            $results[] = [
                'img_sub_url' => $this->_filepath($category->hospital_image),
                'img_sub_alt' => $category->hospital_image->memo1 ?? ''
                ];
        }

        return $results;
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
                && $c->image_order === 3;
        });

        $texts = $categories->map(function ($t) {
            $title = $t->title ?? '';
            $caption = $t->caption ?? '';

            if (strstr($caption, '<p class="s-movie">', true)) {
                $caption = strstr($caption, '<p class="s-movie">', true);
            }
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
            return isset($c->image_order)
                && $c->image_order == 5;
        });

        $results = [];
        foreach ($categories as $i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            $title = $i->title ?? '';
            $desc = $i->caption ?? '';
            $img_pos = $i->image_order->image_location_number ?? '';

            $results[] = [
                'img_url' => $url,
                'img_alt' => $alt,
                'title' => $title,
                'desc' => $desc,
                'img_pos' => $img_pos,
            ];

        }

        return $results;
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
            return isset($c->image_order)
                && $c->image_order == 8;
        });

        $results = [];
        foreach ($categories as $i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            $desc = $i->hospital_image->memo1 ?? '';
            $img_pos = $i->file_location_no ?? '';
            $results[] = [
                'img_url' => $url,
                'img_alt' => $alt,
                'desc' => $desc,
                'type' => $img_pos,
            ];
        }

        return $results;
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
            return isset($c->image_order)
                && $c->image_order == 9;
        });

        $images = $categories->map(function ($t) {
            $img_url = '';
            $img_alt = '';
            if ($t->hospital_image->is_display === 1) { // 写真
                $img_url = $this->_filepath($t->hospital_image);
                $img_alt = $t->caption ?? '';
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
                && $c->image_order === 4;
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
            return isset($c->image_order)
                && $c->image_order === 6;
        });

        $results = [];

        foreach ($categories as $i) {
            if (empty($i->hospital_image)) {
                continue;
            }
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->title ?? '';
            $title = $i->title ?? '';
            $desc = $i->caption ?? '';
            $contents = '';
            if (!empty($i->interview_details)) {
                $contents = '<ul>';
                foreach ($i->interview_details as $detail) {
                    $contents = $contents . '<li>';
                    $contents = $contents . '<h4>' . $detail->question . '</h4>';
                    $contents = $contents . '<p>' . $detail->answer . '</p>';
                    $contents = $contents . '</li>';
                }
                $contents = $contents . '</ul>';
            }
            $results[] = [
                'img_url' => $url,
                'img_alt' => $alt,
                'title' => $title ?? '',
                'desc' => $desc ?? '',
                'contents' => $contents,
            ];
        }

        return $results;
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
            return isset($c->image_order)
                && $c->image_order == 7;
        });

        $results = [];
        foreach ($categories as $i) {
            $url = $this->_filepath($i->hospital_image);
            $alt = $i->hospital_image->memo1 ?? '';
            $name = $i->name ?? '';
            $bio = $i->career ?? '';
            $comment = $i->memo ?? '';
            $results[] = [
                'img_url' => $url,
                'img_alt' => $alt,
                'name' => $name,
                'bio' => $bio,
                'comment' => $comment
            ];
        }

        if (empty($results)) {
            $results[] = ['', '', '', '', ''];
        }

        return $results;
    }

    private function _filepath($hospital_image)
    {
        if (!isset($hospital_image)) return '';
        $path = $hospital_image->path ?? '';
        return $path ;
    }
}
