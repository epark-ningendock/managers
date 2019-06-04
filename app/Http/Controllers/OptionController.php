<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionformStore;
use App\Option;
use App\TaxClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:hospital_staffs', ['except' => 'index', 'edit']);
    }

    public function index()
    {
        $pagination = config('epark.pagination.option_index');
        $options    = Option::orderBy('order')->paginate($pagination);

        return view('option.index', [ 'options' => $options ]);
    }


    public function create()
    {
        $tax_classes = TaxClass::all();
        return view('option.create', [ 'tax_classes' => $tax_classes ]);
    }


    public function store(OptionformStore $request)
    {
        $request->request->add([
            'hospital_id' => session()->get('hospital_id'),
            'order'       => 1,
        ]);
        Option::create($request->all());

        return redirect(route('option.index'))->with('success', trans('messages.created', ['name' => trans('messages.option_name')]));
    }

    public function edit($id)
    {
        $option = Option::findOrFail($id);
        $tax_classes = TaxClass::all();
        return view('option.edit', ['option' => $option, 'tax_classes' => $tax_classes]);
    }


    public function update(Request $request, $id)
    {
        $option = Option::findOrFail($id);
        $request->request->add([
            'hospital_id' => session()->get('hospital_id')
        ]);
        $option->update($request->all());

        return redirect(route('option.index'))->with('success', trans('messages.updated', ['name' => trans('messages.option_name')]));
    }


    public function destroy($id)
    {
        $option = Option::findOrFail($id);
        $option->delete();

        return redirect(route('option.index'))->with('success', trans('messages.deleted', ['name' => trans('messages.option_name')]));
    }


    public function sort()
    {
        $options = Option::orderBy('order')->get();
        return view('option.sort', ['options' => $options]);
    }


    public function updateSort(OptionformStore $request)
    {
        $ids = $request->input('option_ids');
        $options = Option::whereIn('id', $ids)->get();

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
