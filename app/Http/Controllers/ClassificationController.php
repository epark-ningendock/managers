<?php

namespace App\Http\Controllers;

use App\ClassificationType;
use App\Enums\Status;
use App\Http\Requests\ClassificationFormRequest;
use App\MajorClassification;
use App\MiddleClassification;
use Illuminate\Http\Request;
use App\MinorClassification;
use Illuminate\Support\Facades\DB;

class ClassificationController extends Controller
{
    /**
     * classification list
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $c_types = ClassificationType::withTrashed()->get();
        $c_majors = MajorClassification::withTrashed()->get();
        $c_middles = MiddleClassification::withTrashed()->get();


        [$classifications, $result] = $this->getClassifications($request);
        return view('classification.index')->with('classifications', $classifications)
            ->with('result', $result)
            ->with('c_types', $c_types)
            ->with('c_majors', $c_majors)
            ->with('c_middles', $c_middles)
            ->with($request->input());
    }

    /**
     * Update Classification status to Deleted
     * @param $id Classification ID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Request $request)
    {
        $classification = $request->input('classification', 'minor');
        if ($classification == 'major') {
            $item = MajorClassification::find($id);
            if ($item->middle_classifications->count() > 0) {
                $request->session()->flash('error', trans('messages.major_classification.child_exist_error_on_delete'));
                return redirect()->back();
            }
        } elseif ($classification == 'middle') {
            $item = MiddleClassification::find($id);
            if ($item->minor_classifications->count() > 0) {
                $request->session()->flash('error', trans('messages.middle_classification.child_exist_error_on_delete'));
                return redirect()->back();
            }
        } else {
            $item = MinorClassification::find($id);
        }

        $item->delete();
        $request->session()->flash('success', trans('messages.deleted', ['name' => trans('messages.names.classifications.'.$classification)]));
        return redirect()->back();
    }

    /**
     * Restore Deleted Classification
     * @param $id Classification ID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id, Request $request)
    {
        $classification = $request->input('classification', 'minor');
        if ($classification == 'major') {
            $item = MajorClassification::withTrashed()->findOrFail($id);
        } elseif ($classification == 'middle') {
            $item = MiddleClassification::withTrashed()->with('major_classification')->findOrFail($id);
            if ($item->major_classification->trashed()) {
                $request->session()->flash('error', trans('messages.middle_classification.parent_deleted_error_on_restore'));
                return redirect()->back();
            }
        } else {
            $item = MinorClassification::withTrashed()->with('middle_classification')->findOrFail($id);
            if ($item->middle_classification->trashed()) {
                $request->session()->flash('error', trans('messages.minor_classification.parent_deleted_error_on_restore'));
                return redirect()->back();
            }
        }
        $item->restore();
        $request->session()->flash('success', trans('messages.restored', ['name' => trans('messages.names.classifications.'.$classification)]));
        return redirect()->back();
    }


    /**
     * get classification list
     * @param Request $request
     * @param bool $filterByStatus
     * @param bool $isPaginate
     * @return array
     */
    protected function getClassifications(Request $request, $filterByStatus=true, $isPaginate=true)
    {
        $classification = $request->input('classification', 'minor');
        if ($classification == 'major') {
            $query = MajorClassification::query();
            $query->select(
                'major_classifications.id',
                'major_classifications.status',
                'major_classifications.updated_at',
                'major_classifications.order',
                'major_classifications.name as major_name'
            );
            $main_table = 'major_classifications';
        } elseif ($classification == 'middle') {
            $query = MiddleClassification::join('major_classifications', 'middle_classifications.major_classification_id', '=', 'major_classifications.id');
            $query->select(
                'middle_classifications.id',
                'middle_classifications.status',
                'middle_classifications.updated_at',
                'middle_classifications.order',
                'middle_classifications.name as middle_name',
                'major_classifications.name as major_name'
            );
            $main_table = 'middle_classifications';
        } else {
            $query = MinorClassification::join('middle_classifications', 'minor_classifications.middle_classification_id', '=', 'middle_classifications.id')
                ->join('major_classifications', 'minor_classifications.major_classification_id', '=', 'major_classifications.id');
            $query->select(
                'minor_classifications.id',
                'minor_classifications.name as minor_name',
                'minor_classifications.status',
                'minor_classifications.updated_at',
                'minor_classifications.order',
                'middle_classifications.name as middle_name',
                'major_classifications.name as major_name'
            );
            $main_table = 'minor_classifications';
        }

        if ($request->input('type', '') != '') {
            $type = (int)$request->input('type');
            $query->where('major_classifications.classification_type_id', $type);
        }

        if ($request->input('major', '') != '') {
            $major = (int)$request->input('major');
            $query->where('major_classifications.id', $major);
        }

        if (($classification == 'minor' || $classification == 'middle') && $request->input('middle', '') != '') {
            $middle = (int)$request->input('middle');
            $query->where('middle_classifications.id', $middle);
        }

        if ($filterByStatus) {
            if ($request->input('status', Status::Valid) == Status::Deleted) {
                $query->onlyTrashed();
            }
        } else {
            $query->withTrashed();
        }

        $query->orderBy($main_table.'.order', 'ASC');

        if ($isPaginate) {
            $result = $query->paginate(10)
                ->appends($request->query());
        } else {
            $result = $query->get();
        }

        $classifications = collect();

        foreach ($result as $record) {
            $item = array();
            $item['major_name'] = $record['major_name'];
            $item['middle_name'] = $record['middle_name'];
            $item['minor_name'] = $record['minor_name'];
            $item['status'] = $record->status;
            $item['updated_at'] = $record->updated_at_str;
            $item['id'] = $record->id;
            $item['order'] = $record->order;
            $classifications->push($item);
        }


        return [$classifications, $result];
    }

    /**
     * Classification sort
     * @param Request $request
     * @return mixed
     */
    public function sort(Request $request)
    {
        $c_types = ClassificationType::orderBy('name', 'ASC')->get();
        $c_majors = MajorClassification::withTrashed()->get();
        $c_middles = MiddleClassification::withTrashed()->get();

        [$classifications, $result] = $this->getClassifications($request, false, false);

        return view('classification.sort')->with('classifications', $classifications)
            ->with('c_types', $c_types)
            ->with('c_majors', $c_majors)
            ->with('c_middles', $c_middles)
            ->with($request->input());
    }

    /**
     * @param ClassificationFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateSort(ClassificationFormRequest $request)
    {
        $classification = $request->input('classification');
        $ids = $request->input('classification_ids');
        if ($classification == 'major') {
            $class = MajorClassification::class;
        } elseif ($classification == 'middle') {
            $class = MiddleClassification::class;
        } else {
            $class = MinorClassification::class;
        }

        $items = $class::withTrashed()->whereIn('id', $ids)->get();

        if (count($ids) != $items->count()) {
            $request->session()->flash('error', trans('messages.invalid_classification_id'));
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                $index = array_search($item->id, $ids, false);
                $item->order = $index + 1;
                $item->save();
            }
            DB::commit();
            $request->session()->flash('success', trans('messages.classification_sort_updated'));
            return redirect('classification');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', trans('messages.create_error'));
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $classification_types = ClassificationType::all();
        $c_majors = MajorClassification::all();
        $c_middles = MiddleClassification::all();
        $type = $request->input('classification', 'minor');

        return view('classification.create')
            ->with('type', $type)
            ->with('c_majors', $c_majors)
            ->with('c_middles', $c_middles)
            ->with('classification_types', $classification_types);
    }

    /**
     * store classification
     * @param ClassificationFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ClassificationFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $type = $request->input('classification');
            $data = $request->only(['name', 'status']);
            if ($type == 'major') {
                $data = array_merge($data, $request->only(['classification_type_id']));
                $class = MajorClassification::class;
            } elseif ($type == 'middle') {
                $data = array_merge($data, $request->only(['major_classification_id', 'is_icon', 'icon_name']));
                $class = MiddleClassification::class;
            } else {
                $data = array_merge($data, $request->only(['major_classification_id', 'middle_classification_id', 'is_icon', 'icon_name', 'is_fregist', 'max_length']));
                $class = MinorClassification::class;
            }

            $classification = new $class($data);
            $classification->save();

            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.classification')]));
            DB::commit();
            return redirect('classification');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.create_error'))->withInput();
        }
    }

    /**
     * Display classification edit form to edit
     * @param $id Classification ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $type = $request->input('classification');
        $classification_types = ClassificationType::all();
        $c_majors = MajorClassification::all();
        $c_middles = MiddleClassification::all();

        if ($type == 'major') {
            $class = MajorClassification::class;
        } elseif ($type == 'middle') {
            $class = MiddleClassification::class;
        } else {
            $class = MinorClassification::class;
        }
        $classification = $class::findOrFail($id);

        return view('classification.edit')
            ->with('type', $type)
            ->with('classification_types', $classification_types)
            ->with('c_majors', $c_majors)
            ->with('c_middles', $c_middles)
            ->with('classification', $classification);
    }

    /**
     * Update staff
     * @param ClassificationFormRequest $request
     * @param $id Staff ID
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ClassificationFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $type = $request->input('classification');

            $data = $request->only(['name', 'status']);
            if ($type == 'major') {
                $class = MajorClassification::class;
            } elseif ($type == 'middle') {
                $class = MiddleClassification::class;
                $data = array_merge($data, $request->only(['is_icon', 'icon_name']));
            } else {
                $class = MinorClassification::class;
                $data = array_merge($data, $request->only(['is_icon', 'icon_name', 'is_fregist', 'max_length']));
            }
            $classification = $class::findOrFail($id);
            $classification->fill($data);
            $classification->save();

            $request->session()->flash('success', trans('messages.updated', ['name' => trans('messages.names.classification')]));
            DB::commit();
            return redirect('classification');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.staff_create_error'))->withInput();
        }
    }
}
