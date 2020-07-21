<?php

namespace App\Http\Controllers\Api;
use App\ContractInformation;
use App\Course;
use Illuminate\Routing\Controller;
use App\Http\Requests\SearchRequest;

class ApiBaseController extends Controller
{
    protected $messages;

    public function __construct()
    {
        $this->messages = [
            'system_error_db' => [
                'status' => 1,
                'status_code' => 200,
                'code_number' => '02',
                'code_detail' => '01',
                'message' => trans('validation.for_api.system_error_db'),
            ],
            'system_error_api' => [
                'status' => 1,
                'status_code' => 200,
                'code_number' => '02',
                'code_detail' => '02',
                'message' => trans('validation.for_api.system_error_api'),
            ],
            'data_empty_error' => [
                'status' => 1,
                'status_code' => 200,
                'code_number' => '01',
                'code_detail' => '13',
                'message' => trans('validation.for_api.data_empty_error'),
            ],
            'errorDB' => [
                'status' => 1,
                'status_code' => 200,
                'code_number' => '01',
                'code_detail' => '13',
                'message' => trans('validation.for_api.errorDB'),
            ],
            'success' =>[
                'status' => 0,
                'status_code' => 200,
                'message_id' =>'00000',
                'message' =>trans('messages.for_api.success'),
            ],
        ];
    }

    /**
     * レスポンスを生成する
     *
     * @param array $message
     * @return response
     */
    protected function createResponse(array $message, $callback = null) {
        if(!$message) {
            $message = [
                'status' => 1,
                'status_code' => 200,
                'code_number' => '03',
                'code_detail' => '01',
                'message' => ' '
            ];
        }
        $message = collect($message);

        return response()->json($message->except(['status_code']), $message['status_code'])
            ->setCallback($callback);
    }


    /**
     * @param SearchRequest $request
     * @return
     */
    protected function checkSearchCond(SearchRequest $request, $count_only_flag) {

        $return_flag = $request->input('return_flag');
        $return_from = $request->input('return_from');
        $return_to = $request->input('return_to');
        $course_price_sort = $request->input('course_price_sort');
        $search_count_only_flag = $request->input('search_count_only_flag');
        $search_condition_return_flag = $request->input('search_condition_return_flag');

        if (!isset($return_flag)) {
            return [false, $this->messages['required_error']];
        }

        if (!is_numeric($return_flag)) {
            return [false, $this->messages['data_type_error']];
        }

        if ($return_flag == 1) {
            if (!isset($return_from) && !isset($return_to)) {
                return [false, $this->messages['required_with_cond_error']];
            } elseif (isset($return_from) && !is_numeric($return_from)) {
                return [false, $this->messages['data_type_error']];
            } elseif(isset($return_from) && $return_from == 0) {
                return [false, $this->messages['data_range_error']];
            } elseif (isset($return_to) && !is_numeric($return_to)) {
                return [false, $this->messages['data_type_error']];
            } elseif(isset($return_to) && $return_to == 0) {
                return [false, $this->messages['data_range_error']];
            }
        }

        if ($count_only_flag) {
            if (!isset($search_count_only_flag)) {
                return [false, $this->messages['required_error']];
            }
            if (!is_numeric($search_count_only_flag) || !in_array($search_count_only_flag, [0,1])) {
                return [false, $this->messages['data_range_error']];
            }
        } else {
            if (!isset($course_price_sort)) {
                return [false, $this->messages['required_error']];
            }
            if (!is_numeric($course_price_sort) || !in_array($search_count_only_flag, [0,1])) {
                return [false, $this->messages['data_range_error']];
            }
        }


        if (!isset($search_condition_return_flag)) {
            return [false, $this->messages['required_error']];
        }
        if (!is_numeric($search_condition_return_flag) || !in_array($search_condition_return_flag, [0,1])) {
            return [false, $this->messages['data_range_error']];
        }

        return [true];
    }

    protected function convert_sex($medical_examination_system_id, $sex) {
        if ($medical_examination_system_id == 1) {
            if ($sex == 0) {
                return 1;
            } else {
                return 2;
            }
        } else {
            if ($sex == 0) {
                return 0;
            } else {
                return 1;
            }
        }

    }
}
