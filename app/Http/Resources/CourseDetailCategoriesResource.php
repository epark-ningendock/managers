<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CourseDetailCategoriesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $results = [];
        if(!isset($this->course_details)) {
            return $results;
        }

        foreach ($this->course_details as $detail) {

            if ($detail->select_status != 1 || $detail->status != 1) {
                continue;
            }

            $keyIndex = array_search($detail->major_classification_id, array_column($results, 'id'));

            if ($keyIndex === false) {
                $major = [
                    'id' => $detail->major_classification_id,
                    'keyindex' => $keyIndex,
                    'title' => $this->major_classification->icon_name,
                    'type_no' => $this->major_classification->classification_type_id,
                    'category_middle' => $this->getMiddle($this->middle_classification, $this->minor_classification)];
                $results[] = $major;
            } else {
                $major = $results[$keyIndex];
                $middleKeyIndex = array_search($detail->middle_classification_id, array_column($major['category_middle'], 'id'));
                if ($middleKeyIndex === false) {
                    $middle = $this->getMiddle($this->middle_classification, $this->minor_classification);
                    $category_middle = $major['category_middle'];
                    $major['category_middle'] = array_merge($category_middle, $middle);
                } else {
                    if (isset($major['category_middle'][$middleKeyIndex])) {
                        $middle = $major['category_middle'][$middleKeyIndex];
                        $minorKeyIndex = array_search($detail->minor_classification_id, array_column($middle['category_small'], 'id'));
                        if ($minorKeyIndex === false) {
                            $minor = $this->getMinor($this->minor_classification);
                            $category_small = $middle['category_small'];
                            $middle['category_small'] = array_merge($category_small, $minor);
                        }
                    }
                }
            }
        }
    }

    private function getMiddle($middle_classification, $minor_classfication) {

        return ['id' => $middle_classification->id,
            'title' => $middle_classification->icon_name,
            'category_small' => $this->getMinor($minor_classfication)];
    }

    private function getMinor($minor_classfication) {
        return ['id' => $minor_classfication->id,
            'title' => $minor_classfication->icon_name,
            'icon' => $minor_classfication->name];
    }
}
