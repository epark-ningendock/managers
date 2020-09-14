<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseOption;
use App\Http\Requests\OptionformStore;
use App\Option;
use App\TaxClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Reshadman\OptimisticLocking\StaleModelLockingException;

class OptionController extends Controller
{
    /**
     * オプション管理初期表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pagination = config('epark.pagination.option_index');
        $options    = Option::with('course_options')->where('hospital_id', session()->get('hospital_id'))->orderBy('order')->paginate($pagination);

        foreach($options as $option){
        	$courseIds = $option->course_options->pluck('course_id')->all();
        	$courses = (!empty($courseIds)) ? Course::whereIn('id', $courseIds)->orderBy('order')->get(['id', 'name']) : [];
        	$option->courses = $courses;
				}

        return view('option.index', [ 'options' => $options ]);
    }

    /**
     * オプション作成
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $tax_classes = TaxClass::all();
				$course = Course::where('hospital_id', session()->get('hospital_id'))->get(['id', 'name']);
        return view('option.create', [ 'tax_classes' => $tax_classes, 'courses' => $course, 'applied' => [] ]);
    }

    /**
     * オプション登録
     * @param OptionformStore $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OptionformStore $request)
    {
    	try{
    		DB::beginTransaction();

				$request->request->add([
					'hospital_id' => session()->get('hospital_id'),
					'order'       => 0,
				]);
				$option = Option::create($request->all());

				// 紐付けコース（CourseOption）の登録
				foreach($request->courses as $course){
					$applied = new CourseOption();
					$applied->option_id = $option->id;
					$applied->course_id = $course;
					$applied->save();
				}

				DB::commit();

				return redirect(route('option.index'))->with('success', trans('messages.created', ['name' => trans('messages.option_name')]));
			} catch (StaleModelLockingException $e) {
				DB::rollback();
				return redirect()->back()->with('error', trans('messages.model_changed_error'));
			} catch (Exception $e) {
				DB::rollback();
				return redirect()->back()->with('error', trans('messages.update_error'))->withInput();
			}
    }

    /**
     * オプション編集
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $option = Option::where('id', $id)->where('hospital_id', session()->get('hospital_id'))->first();
        if (!isset($option)) abort(404);

        $course = Course::where('hospital_id', session()->get('hospital_id'))->get(['id', 'name']);
        $applied = CourseOption::where('option_id', $id)->get(['course_id'])->toArray();
        $applied = array_column($applied, 'course_id');

        $tax_classes = TaxClass::all();
        return view('option.edit', ['option' => $option, 'tax_classes' => $tax_classes, 'courses' => $course, 'applied' => $applied]);
    }

    /**
     * オプション更新
     * @param OptionformStore $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OptionformStore $request, $id)
    {
        try{
            DB::beginTransaction();

            $option = Option::where('id', $id)->where('hospital_id', session()->get('hospital_id'))->first();
            if (!isset($option)) abort(404);

            $request->request->add([
                'hospital_id' => session()->get('hospital_id')
            ]);
            $option->fill($request->all())->save();

            // 紐付けコース（CourseOption）の更新
						CourseOption::where('option_id', $id)->forceDelete();
						foreach($request->courses as $course){
							$applied = new CourseOption();
							$applied->option_id = $id;
							$applied->course_id = $course;
							$applied->save();
						}

            DB::commit();

            return redirect(route('option.index'))->with('success', trans('messages.updated_common'));
        } catch (StaleModelLockingException $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.model_changed_error'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', trans('messages.update_error'))->withInput();
        }
    }

    /**
     * オプション削除
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $option = Option::findOrFail($id);

        CourseOption::where('option_id', $id)->delete();

        $option->delete();

        return redirect(route('option.index'))->with('success', trans('messages.deleted', ['name' => trans('messages.option_name')]));
    }

    /**
     * 並び替え
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sort()
    {
        $options = Option::where('hospital_id', session()->get('hospital_id'))->orderBy('order')->get();
        return view('option.sort', ['options' => $options]);
    }

    /**
     * 並び順更新
     * @param OptionformStore $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateSort(OptionformStore $request)
    {
        $ids = $request->input('option_ids');
        $options = Option::where('hospital_id', session()->get('hospital_id'))->whereIn('id', $ids)->get();

        if (count($ids) != $options->count()) {
            $request->session()->flash('error', trans('messages.invalid_option_id'));
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            foreach ($options as $option) {
                $index = array_search($option->id, $ids, false);
                $option->order = $index + 1;
                $option->save();
            }
            DB::commit();
            $request->session()->flash('success', trans('messages.option_sorting_updated'));
            return redirect('option');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', trans('messages.create_error'));
            return redirect()->back();
        }
    }
}
