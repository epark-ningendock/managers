<?php

namespace App\Http\Controllers;

use App\ClassificationType;
use App\Enums\Status;
use App\MajorClassification;
use App\MiddleClassification;
use Illuminate\Http\Request;
use App\MinorClassification;

class ClassificationController extends Controller
{
    public function index(Request $request)
    {
        $c_types = ClassificationType::all();
        $c_majors = MajorClassification::all();
        $c_middles = MiddleClassification::all();

        $classification = $request->input('classification', 'minor');

        if ($classification == 'major') {
            $query = MajorClassification::query();
            $query->select('major_classifications.id',
                'major_classifications.status',
                'major_classifications.updated_at',
                'major_classifications.name as major_name');
        } else if ($classification == 'middle') {
            $query = MiddleClassification::join('major_classifications', 'middle_classifications.major_classification_id', '=', 'major_classifications.id');
            $query->select('middle_classifications.id',
                'middle_classifications.status',
                'middle_classifications.updated_at',
                'middle_classifications.name as middle_name',
                'major_classifications.name as major_name');
        } else {
            $query = MinorClassification::join('middle_classifications', 'minor_classifications.middle_classification_id', '=', 'middle_classifications.id')
                ->join('major_classifications', 'minor_classifications.major_classification_id', '=', 'major_classifications.id');
            $query->select('minor_classifications.id',
                'minor_classifications.name as minor_name',
                'minor_classifications.status',
                'minor_classifications.updated_at',
                'middle_classifications.name as middle_name',
                'major_classifications.name as major_name');
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

        if ($request->input('status', '1') == Status::Deleted) {
            $query->onlyTrashed();
        }

        $result = $query->paginate(10)
                        ->appends($request->query());

        $classifications = collect();

        foreach ($result as $record) {
            $item = array();
            $item['major_name'] = $record['major_name'];
            $item['middle_name'] = $record['middle_name'];
            $item['minor_name'] = $record['minor_name'];
            $item['status'] = $record->status;
            $item['updated_at'] = $record->updated_at_str;
            $item['id'] = $record->id;
            $classifications->push($item);
        }

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
        } else if ($classification == 'middle') {
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
        } else if ($classification == 'middle') {
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

}
