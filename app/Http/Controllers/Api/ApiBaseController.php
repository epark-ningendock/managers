<?php

namespace App\Http\Controllers\Api;
use App\ContractInformation;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;

class ApiBaseController extends Controller
{
    protected $messages;

    public function __construct()
    {
        $this->messages = config('api.search_api.message');
    }

    /**
     * レスポンスを生成する
     *
     * @param array $message
     * @return response
     */
    protected function createResponse(array $message) {
        return response([
            'status' => strval($message['status']),
            'code_number' => $message['error_no'],
            'code_detail' => $message['detail_no'],
            'message' => $message['description']
        ], $message['http_status'])->header('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * 指定の医療機関コードが存在しているかチェックする
     *
     * @param $hospital_code
     *
     * @return
     */
    protected function isExistHospitalCode($hospital_code) {

        $contract_information = ContractInformation::where('code', $hospital_code)->first();
        if ($contract_information) {
            return $contract_information->hospital_id;
        } else {
            return null;
        }
    }

    /**
     * 指定の検査コースが存在しているかチェックする
     *
     * @param $hopital_id
     * @param $course_no
     *
     * @return
     */
    protected function isExistCouse($hopital_id, $course_no) {

        $course = Course::where('id', $course_no)
            ->where('hospital_id', $hopital_id)->first();
        if ($course) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $hospital_code
     * @return array
     */
    protected function checkHospitalCode($hospital_code) {

        if (!isset($hospital_code)) {
            return [false, $this->messages['required_error']];
        }

        if (!preg_match('/^D[0-9a-zA-Z]+$/u', $hospital_code)) {
            return [false, $this->messages['data_type_error']];
        }

        $hospital_id = $this->isExistHospitalCode($hospital_code);

        if (!isset($hospital_id)) {
            return [false, $this->messages['data_empty_error']];
        }

        return [true, $hospital_id];
    }

    /**
     * @param $course_no
     * @param $hospital_id
     * @return array
     */
    protected function checkCourseNo($course_no, $hospital_id) {

        if (!isset($course_no)) {
            return [false, $this->messages['required_error']];
        }

        if (!is_numeric($course_no)) {
            return [false, $this->messages['data_type_error']];
        }

        if (!$this->isExistCouse($hospital_id, $course_no)) {
            return [false, $this->messages['data_empty_error']];
        }

        return [true];
    }

    /**
     * @param $yyyyMM
     * @return array
     */
    protected function checkMonthDate($yyyyMM) {

        if (!is_numeric($yyyyMM)) {
            return [false, $this->messages['data_type_error']];
        }

        if (!preg_match('/^[0-9]{6}$/u', $yyyyMM)) {
            return [false, $this->messages['data_type_error']];
        }

        return [true];
    }

    /**
     * @param $yyyyMM
     * @return array
     */
    protected function checkDayDate($yyyyMMdd) {

        if (!is_numeric($yyyyMMdd)) {
            return [false, $this->messages['data_type_error']];
        }

        if (!preg_match('/^[0-9]{8}$/u', $yyyyMMdd)) {
            return [false, $this->messages['data_type_error']];
        }

        return [true];
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

    /**
     * @param $place_code
     * @return array
     */
    protected function checkPlaceCode($place_code) {

        if (empty($place_code)) {
            return [true];
        }

        if (!is_numeric($place_code)) {
            return [false, $this->messages['data_type_error']];
        }

        if ($place_code < 0 || $place_code > 47) {
            return [false, $this->messages['data_range_error']];
        }
        return [true];
    }

    /**
     * @param $rail_no
     * @return array
     */
    protected function checkRailNo($rail_no) {

        if (empty($rail_no)) {
            return [true];
        }

        if (!is_numeric($rail_no)) {
            return [false, $this->messages['data_type_error']];
        }
        return [true];
    }
}
