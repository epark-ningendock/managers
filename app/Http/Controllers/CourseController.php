<?php

namespace App\Http\Controllers;

use App\ContractInformation;
use App\Course;
use App\CourseDetail;
use App\CourseImage;
use App\CourseMatch;
use App\CourseMeta;
use App\CourseOption;
use App\CourseQuestion;
use App\Enums\CourseImageType;
use App\Enums\WebReception;
use App\Hospital;
use App\HospitalImage;
use App\HospitalMeta;
use App\HospitalStaff;
use App\Http\Requests\CourseFormRequest;
use App\KenshinSysCourse;
use App\MajorClassification;
use App\MinorClassification;
use App\Staff;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Option;
use App\ImageOrder;
use App\TaxClass;
use App\Calendar;
use Mockery\Exception;
use Reshadman\OptimisticLocking\StaleModelLockingException;
use Illuminate\Support\Facades\Mail;
use App\Mail\Course\CourseSettingNotificationMail;
use Illuminate\Support\Facades\Auth;
use App\Enums\Permission;

class CourseController extends Controller
{
    /**
     * Display a listing of the course.
     * @return \Illuminate\Contracts\View\Factory\Illuminate\View\View
     */
    public function index()
    {
        $courses = Course::where('hospital_id', session()->get('hospital_id'))
            ->orderBy('order')->paginate(50);
        return view('course.index', ['courses' => $courses]);
    }

    /**
     * * Show the form for creating a new resource.
     * @return mixed
     */
    public function create()
    {
        
        $hospital_id = session()->get('hospital_id');
        $hospital = Hospital::find($hospital_id);
        $images = HospitalImage::where('hospital_id', $hospital_id)->get();
        $majors = MajorClassification::orderBy('classification_type_id', 'asc')->orderBy('order', 'asc')->get();
        $options = Option::where('hospital_id', $hospital_id)->orderBy('order')->get();
        $image_orders = ImageOrder::orderBy('order')->get();
        $calendars = Calendar::where('hospital_id', $hospital_id)->get();
        $disp_date_start = Carbon::today()->addDay(7)->format('Y-m-d');
        $disp_date_end = Carbon::today()->addMonth(12)->format('Y-m-d');
        $today = Carbon::today();
        $tax_class = TaxClass::whereDate('life_time_from', '<=', $today)
            ->whereDate('life_time_to', '>=', $today)->get()->first();
        $kenshin_sys_courses = KenshinSysCourse::with(['kenshin_sys_dantai_info'])
            ->where('kenshin_sys_hospital_id', $hospital->kenshin_sys_hospital_id)->get();
        $course_matches = collect();

        $is_presettlement = $hospital->is_pre_account == '1' &&
					(isset(Auth::user()->staff_auth)) &&
            (Auth::user()->staff_auth->is_pre_account == Permission::EDIT
                || Auth::user()->staff_auth->is_pre_account == Permission::UPLOAD);

        return view('course.create')
            ->with('calendars', $calendars)
            ->with('tax_class', $tax_class)
            ->with('image_orders', $image_orders)
            ->with('options', $options)
            ->with('majors', $majors)
            ->with('disp_date_start', $disp_date_start)
            ->with('disp_date_end', $disp_date_end)
            ->with('hospital', $hospital)
            ->with('images', $images)
            ->with('kenshin_sys_courses', $kenshin_sys_courses)
            ->with('course_matches', $course_matches)
            ->with('is_presettlement', $is_presettlement);
    }

    /**
     * course copy
     * @param $id
     * @return mixed
     */
    public function copy($id)
    {
        $courses = Course::findOrFail($id);

        $hospital_id = session()->get('hospital_id');
        if (isset($hospital_id) && $hospital_id != $courses->hospital_id) {
            abort(404);
        }

        return $this->create()->with('course', $courses);
    }

    /**
     * Store a newly created resource in storage.
     * @param CourseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(CourseFormRequest $request)
    {
        try {
            $course = $this->saveCourse($request, null);
						self::postBacklog($this->setParamBacklog($request, $course, 'create'));

//            Mail::to(config('mail.to.system'))->send(new CourseSettingNotificationMail($data));
            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.course')]));
            return redirect('course');
        } catch (Exception $e) {
            $request->session()->flash('error', trans('messages.create_error'));
            return redirect()->back()->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $hospital_id = session()->get('hospital_id');

        if (isset($hospital_id) && $hospital_id != $course->hospital_id) {
            abort(404);
        }
        $hospital_id = session()->get('hospital_id');
        $hospital = Hospital::find($hospital_id);
        $images = HospitalImage::where('hospital_id', $hospital_id)->get();
        $majors = MajorClassification::orderBy('classification_type_id', 'asc')->orderBy('order', 'asc')->get();
        $options = Option::where('hospital_id', $hospital_id)->orderBy('order')->get();
        $image_orders = ImageOrder::orderBy('order')->get();
        $calendars = Calendar::where('hospital_id', $hospital_id)->get();
        $disp_date_start = Carbon::today()->addDay(7)->format('Y-m-d');
        $disp_date_end = Carbon::today()->addMonth(12)->format('Y-m-d');
        $today = Carbon::today();
        $tax_class = TaxClass::whereDate('life_time_from', '<=', $today)
            ->whereDate('life_time_to', '>=', $today)->get()->first();

        $is_presettlement = $hospital->is_pre_account == '1' &&
					(isset(Auth::user()->staff_auth)) &&
            (Auth::user()->staff_auth->is_pre_account == Permission::EDIT
                || Auth::user()->staff_auth->is_pre_account == Permission::UPLOAD);

        $kenshin_sys_courses = KenshinSysCourse::with(['kenshin_sys_dantai_info'])
            ->where('kenshin_sys_hospital_id', $hospital->kenshin_sys_hospital_id)->get();
        $course_matches = $course->kenshin_sys_courses()->get();

        return view('course.edit')
            ->with('calendars', $calendars)
            ->with('tax_class', $tax_class)
            ->with('image_orders', $image_orders)
            ->with('options', $options)
            ->with('majors', $majors)
            ->with('images', $images)
            ->with('disp_date_start', $disp_date_start)
            ->with('disp_date_end', $disp_date_end)
            ->with('hospital', $hospital)
            ->with('course', $course)
            ->with('is_presettlement', $is_presettlement)
            ->with('kenshin_sys_courses', $kenshin_sys_courses)
            ->with('course_matches', $course_matches);
    }

    protected function saveCourse(CourseFormRequest $request, $course_param)
    {
        try {
            DB::beginTransaction();

            $hospital = Hospital::findOrFail(session()->get('hospital_id'));
            //Course
            $course_data = $request->only([
                'hospital_id',
                'name',
                'course_image_main',
                'course_image_pc',
                'course_image_sp',
                'web_reception',
                'calendar_id',
                'is_category',
                'course_point',
                'course_notice',
                'course_cancel',
                'cancellation_deadline',
                'is_price',
                'price',
                'is_price_memo',
                'price_memo',
                'pre_account_price',
                'is_pre_account_price',
                'lock_version',
                'publish_start_date',
                'publish_end_date',
                'is_pre_account',
                'reception_acceptance_day_end',
                'auto_calc_application'
            ]);
            $reception_start_day = $request->input('reception_start_day');
            $reception_start_month = $request->input('reception_start_month');
            $reception_end_day = $request->input('reception_end_day');
            $reception_end_month = $request->input('reception_end_month');
            $reception_acceptance_day = $request->input('reception_acceptance_day');
            $reception_acceptance_month = $request->input('reception_acceptance_month');
            $course_data['hospital_id'] = $request->input('hospital_id');
            $course_data['reception_start_date'] = $reception_start_month * 1000 + $reception_start_day;
            $course_data['reception_end_date'] = $reception_end_month * 1000 + $reception_end_day;
            $course_data['reception_acceptance_date'] = $reception_acceptance_month * 1000 + $reception_acceptance_day;

            $code_store_flg = false;
            if (isset($course_param)) {
                $course = $course_param;
            } else {
                $course = new Course();
                $max_order = Course::where('hospital_id', session()->get('hospital_id'))->max('order');
                $course_data['order'] = $max_order + 1;
                $code_store_flg = true;
            }
            $course->fill($course_data);
            $course->hospital_id = session()->get('hospital_id');
            if ($course->auto_calc_application == '1') {
                $course_price = $course->is_price == '1' ? $course->price : 0;
                $course->pre_account_price =  $course_price * ($hospital->pre_account_discount_rate/100);
            }

            // to clear existing value in edit case
            if($course->is_pre_account == '0') {
                $course->pre_account_price = null;
            }
            //force to update updated_at. otherwise version will not be updated
            $course->touch();
            $course->save();
            if ($code_store_flg) {
                $course->code = 'C' . $course->id . 'H' . $course->hospital_id;
                $course->save();
            }

            //Course Images
            if ($request->has('course_image_main')) {
                $target_image = 'course_image_main';
                $target_type = CourseImageType::MAIN;
                $this->saveCourseImage($request, $target_image, $target_type, $course->id);
            }
            if ($request->has('course_image_pc')) {
                $target_image = 'course_image_pc';
                $target_type = CourseImageType::PC;
                $this->saveCourseImage($request, $target_image, $target_type, $course->id);
            }
            if ($request->has('course_image_sp')) {
                $target_image = 'course_image_sp';
                $target_type = CourseImageType::SP;
                $this->saveCourseImage($request, $target_image, $target_type, $course->id);
            }

            //Course Kenshin
            $kenshin_course_ids = collect($request->input('kenshin_sys_course_ids', []));

            if ($kenshin_course_ids->isNotEmpty()) {
                $kenshin_courses = KenshinSysCourse::whereIn('id', $kenshin_course_ids)->get();
                if ($kenshin_courses->count() != $kenshin_course_ids->count()) {
                    $request->session()->flash('error', trans('messages.invalid_kenshin_course_id'));
                    return redirect()->back();
                }

                $course->kenshin_sys_courses()->sync($kenshin_course_ids);
            } else {

                // 検診システムコースの指定がない場合は、空配列を渡す
                $course->kenshin_sys_courses()->sync(collect());
            }

            //Course Options
            $option_ids = collect($request->input('option_ids', []));

            if ($option_ids->isNotEmpty()) {
                $options = Option::whereIn('id', $option_ids)->get();
                if ($options->count() != $option_ids->count()) {
                    $request->session()->flash('error', trans('messages.invalid_option_id'));
                    return redirect()->back();
                }

                if (isset($course_param)) {
                    $course->course_options()->forceDelete();
                }

                foreach ($option_ids as $option_id) {
                    $course_option = new CourseOption();
                    $course_option->course_id = $course->id;
                    $course_option->option_id = $option_id;
                    $course_option->save();
                }
            } else {
                if (isset($course_param)) {
                    $course->course_options()->forceDelete();
                }
            }

            //Course Detail
            $minor_ids = collect($request->input('minor_ids'), []);
            $minor_values = collect($request->input('minor_values'), []);
            $course_meta = CourseMeta::where('course_id', $course->id)->first();
            if (!$course_meta) {
                $course_meta = new CourseMeta();
                $course_meta->hospital_id = $hospital->id;
                $course_meta->course_id = $course->id;
            }

            $category_exam_name = '';
            $category_disease_name = '';
            $category_part_name = '';
            $category_exam = '';
            $category_disease = '';
            $category_part = '';
            $meal_flg = 0;
            $pear_flg = 0;
            $female_doctor_flg = 0;

            if ($minor_ids->isNotEmpty()) {
                $minors = MinorClassification::whereIn('id', $minor_ids)->orderBy('order')->get();
                if ($minors->count() != count($minor_ids)) {
                    $request->session()->flash('error', trans('messages.invalid_minor_id'));
                    return redirect()->back();
                }

                if (isset($course_param)) {
                    $course->course_details()->forceDelete();
                }
                foreach ($minors as $index => $minor) {
                    $input_index = $minor_ids->search(function ($id) use ($minor) {
                        return $minor->id == $id;
                    });

                    if ($input_index == -1 || ($minor->is_fregist == '1' && $minor_values[$input_index] == 0)
                        || ($minor->is_fregist == '0' && $minor_values[$input_index] == '')) {
                        continue;
                    }


                    $course_detail = new CourseDetail();
                    $course_detail->course_id = $course->id;
                    $course_detail->minor_classification_id = $minor->id;
                    $course_detail->major_classification_id = $minor->major_classification_id;
                    $course_detail->middle_classification_id = $minor->middle_classification_id;
                    if ($minor->is_fregist == '1') {
                        $course_detail->select_status = 1;
                    } else {
                        $course_detail->inputstring = $minor_values[$input_index];
                    }
                    $course_detail->save();

                    if ($minor->major_classification_id == 13 && $minor->is_fregist == '1') {
                        $category_exam_name = $category_exam_name . ' '. $minor->name;
                        $category_exam = $category_exam . ' '. $minor->id;
                    }

                    if ($minor->major_classification_id == 25 && $minor->is_fregist == '1') {
                        $category_disease_name = $category_disease_name . ' '. $minor->name;
                        $category_disease = $category_disease . ' '. $minor->id;
                    }

                    if (($minor->major_classification_id == 2 || $minor->major_classification_id == 3 || $minor->major_classification_id == 4)
                        && $minor->is_fregist == '1') {
                        $category_part_name = $category_part_name . ' '. $minor->name;
                        $category_part = $category_part . ' '. $minor->id;
                    }

                    if ($minor->id == 256 && $minor->is_fregist == '1') {
                        $meal_flg = 1;
                    }

                    if ($minor->id == 132 && $minor->is_fregist == '1') {
                        $pear_flg = 1;
                    }

                    if ($minor->id == 126 && $minor->is_fregist == '1') {
                        $female_doctor_flg = 1;
                    }

                }
            }

            $course_meta->category_exam_name = $category_exam_name;
            $course_meta->category_exam = $category_exam;
            $course_meta->category_disease_name = $category_disease_name;
            $course_meta->category_disease = $category_disease;
            $course_meta->category_part_name = $category_part_name;
            $course_meta->category_part = $category_part;
            $course_meta->meal_flg = $meal_flg;
            $course_meta->pear_flg = $pear_flg;
            $course_meta->female_doctor_flg = $female_doctor_flg;
            $course_meta->save();

            $course_metas = CourseMeta::where('hospital_id', $hospital->id)->get();
            $course_name = '';
            $category_exam_names = '';
            $category_disease_names = '';
            foreach ($course_metas as $c) {
                $course_name = $course_name . ' ' . $c->course_name;
                $category_exam_names = $category_exam_names . ' ' . $c->category_exam_name;
                $category_disease_names = $category_disease_names . ' ' . $c->category_disease_name;
            }
            $hospital_meta = HospitalMeta::where('hospital_id', $hospital->id)->first();
            if ($hospital_meta) {
                $hospital_meta->course_name = $course_name;
                $hospital_meta->category_exam_name = $category_exam_names;
                $hospital_meta->category_disease_name = $category_disease_names;
                $hospital_meta->save();
            }

            //Course Question
            $is_questions = $request->input('is_questions');
            $question_titles = $request->input('question_titles');
            $answer01s = $request->input('answer01s');
            $answer02s = $request->input('answer02s');
            $answer03s = $request->input('answer03s');
            $answer04s = $request->input('answer04s');
            $answer05s = $request->input('answer05s');
            $answer06s = $request->input('answer06s');
            $answer07s = $request->input('answer07s');
            $answer08s = $request->input('answer08s');
            $answer09s = $request->input('answer09s');
            $answer10s = $request->input('answer10s');

            if (isset($course_param)) {
                $course->course_questions()->forceDelete();
            }
            for ($i =  0; $i < 5; $i++) {
                $course_question = new CourseQuestion();
                $course_question->question_number = $i + 1;
                $course_question->course_id = $course->id;
                $course_question->is_question = $is_questions[$i];
                // 利用しないにチェックを入れていても保存したい
                $course_question->question_title = $question_titles[$i];
                $course_question->answer01 = $answer01s[$i];
                $course_question->answer02 = $answer02s[$i];
                $course_question->answer03 = $answer03s[$i];
                $course_question->answer04 = $answer04s[$i];
                $course_question->answer05 = $answer05s[$i];
                $course_question->answer06 = $answer06s[$i];
                $course_question->answer07 = $answer07s[$i];
                $course_question->answer08 = $answer08s[$i];
                $course_question->answer09 = $answer09s[$i];
                $course_question->answer10 = $answer10s[$i];
                $course_question->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        return $course;
    }

    /**
     * コース画像登録
     * @param CourseFormRequest $request
     * @param String $target_image
     * @param String $target_type
     * @param int $course_id
     */
    private function saveCourseImage(CourseFormRequest $request, String $target_image, String $target_type, int $course_id)
    {
        $image = \Image::make(file_get_contents($request->file($target_image)));
        $course_image = CourseImage::firstOrCreate([
            'course_id' => $course_id,
            'type' => $target_type
        ]);

        $name = $course_image->name_for_upload($request->file($target_image)->getClientOriginalName());
        \Storage::disk(env('FILESYSTEM_CLOUD'))->put($name, (string) $image->encode(), 'public');
        $image_path = \Storage::disk(env('FILESYSTEM_CLOUD'))->url($name);

        $course_image_data = [
            'name' => $name,
            'extension' => $request->file($target_image)->getClientOriginalExtension(),
            'path' => $image_path,
        ];
        $course_image->fill($course_image_data);
        $course_image->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourseFormRequest $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseFormRequest $request, Course $course)
    {
        try {
            $request->merge([
                'is_price' => (integer)$request->has('is_price'),
                'is_price_memo' => (integer)$request->has('is_price_memo'),
            ]);
            $_course = clone $course;
            $course = $this->saveCourse($request, $course);
//            Mail::to(config('mail.to.system'))->send(new CourseSettingNotificationMail($data));

						self::postBacklog($this->setParamBacklog($request, $_course, 'update'));

						$request->session()->flash('success', trans('messages.updated', ['name' => trans('messages.names.course')]));
            return redirect('course');
        }  catch(StaleModelLockingException $e) {
            $request->session()->flash('error', trans('messages.model_changed_error'));
            return redirect()->back();
        } catch (Exception $e) {
            $request->session()->flash('error', trans('messages.update_error'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, Request $request)
    {
        $course->course_details()->delete();
        $course->course_options()->delete();
        $course->course_questions()->delete();
        $course->course_images()->delete();
        $course->delete();
//        Mail::to(config('mail.to.system'))->send(new CourseSettingNotificationMail($data));

				self::postBacklog($this->setParamBacklog($request, $course, 'delete'));
        $request->session()->flash('success', trans('messages.deleted', ['name' => trans('messages.names.course')]));
        return redirect()->back();
    }

    /**
     * Course sort
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sort()
    {

        $courses = Course::where('hospital_id', session()->get('hospital_id'))->orderBy('order')->get();
        return view('course.sort')->with('courses', $courses);
    }

    /**
     * update course order
     * @param CourseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateSort(CourseFormRequest $request)
    {
        $ids = $request->input('course_ids');
        $courses = Course::where('hospital_id', session()->get('hospital_id'))
            ->whereIn('id', $ids)->get();

        if (count($ids) != $courses->count()) {
            $request->session()->flash('error', trans('messages.invalid_course_id'));
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            foreach ($courses as $course) {
                $index = array_search($course->id, $ids, false);
                $course->order = $index + 1;
                $course->save();
            }
            DB::commit();
            $request->session()->flash('success', trans('messages.course_sort_updated'));
            return redirect('course');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', trans('messages.create_error'));
            return redirect()->back();
        }
    }

    /**
     * getting course details
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function course_detail($id)
    {
        $course = Course::with([ 'course_options', 'course_options.option', 'course_questions' ])
            ->where('id', $id)
            ->get();
        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $course_image_id
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(int $course_image_id)
    {
        $course = CourseImage::find($course_image_id)->course;
        CourseImage::find($course_image_id)->delete();
        return redirect()->route('course.edit', ['course' => $course])->with('success', trans('messages.deleted', ['name' => trans('messages.names.course_image')]));
    }

	/**
	 * @param Request $request
	 * @param Course $course
	 * @param $process
	 * @return array
	 */
    private function setParamBacklog(Request $request, Course $course, $process){
			$hospital_id = session()->get('hospital_id');
			$hospital = Hospital::find($hospital_id);
			$contract = ContractInformation::where('hospital_id', $hospital_id)->first();
			$operator = (session()->get('isEpark')) ? Staff::find(session()->get('staffs')) : HospitalStaff::find(session()->get('staffs'));

			$kind = [
				'title' => [
					'create' => '新規登録',
					'update' => '変更',
					'delete' => '削除'
				],
				'issue' => [
					'create' => '692428',
					'update' => '692446',
					'delete' => '692505'
				]
			];

			$web_recep = ['公開', '非公開'];
			$c_recep = ($course->web_reception === WebReception::ACCEPT) ? '公開' : '非公開';

			$description = '';

			if($process === 'update'){
				$description = "■コース名：{$course->name}　→　{$request->name}\n\n";
				$description.= "■Web公開：{$c_recep}　→　{$web_recep[$request->web_reception]}\n\n";
				$description.= "■価格：{$course->price}　→　{$request->price}\n\n";
				$description.= "■コース特徴：\n{$course->course_point}\n\nから\n\n{$request->course_point}\n\n";
			}

			$description.="■操作者：{$operator->name}\n";

			return [
				'summary' => "{$hospital->name}様がコースを{$kind['title'][$process]}しました",
				'issueTypeId' => $kind['issue'][$process],
				'description' => $description,
				'categoryId' => ['324353'],
				'customField_101553' => $hospital->name,	// 医療機関名
				'customField_101552' => $course->name,	// コース名
				'customField_101554' => Config('app.url'). "/course/{$course->id}/edit",	// 管理画面URL
				'customField_101561' => env('FRONT_URL'). "detail_hospital/{$contract->code}/course/{$course->code}",	// C画面URL
			];
		}

	/**
	 * @param $params
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
    public static function postBacklog($params){
    	if(session()->get('isEpark') != 'false'){
				// Backlog API設定
				$space = 'docknet';
				$project = 141056;
				$apiKey = '?apiKey=c8OKWdGGr9inkSoyLdLo3ipKKZG6UPXoH6LRP1fCnpIcTQYWvhATPxCtWxKPL5Ol';
				$baseUri = "https://{$space}.backlog.jp/api/v2/";
				$client = new Client(['base_uri' => $baseUri]);

				$params += [
					'projectId' => $project,
					'priorityId' => 3,
					'notifiedUserId' => ['322846', '144863', '176880', '252491'],
				];

				try{
					$res = $client->post('issues'. $apiKey, ['form_params' => $params]);
					\Log::debug($res);
				}catch(\Exception $e){
					\Log::error($e);
				}
			}
		}
}
