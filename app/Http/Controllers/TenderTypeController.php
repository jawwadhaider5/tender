<?php

namespace App\Http\Controllers;
 
use App\Models\TenderType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;  
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TenderTypeController extends Controller
{ 
    function __construct()
    {
        $this->middleware('permission:tender-type', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]); 
    }

    public function index()
    {    

        if (request()->ajax()) {

            $tender_type = TenderType::all(); 

            return Datatables::of($tender_type)
                ->addColumn(
                    'action',
                    '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button> 
            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">  
            @can("tender-type")
                <li><a href="{{action(\'TenderTypeController@edit\', [$id])}}"  class="edit-tender_type text-decoration-none"><i class=" btn btn-sm btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
            @endcan 
            @can("tender-type")
                <li class=""><a href="{{action(\'TenderTypeController@destroy\', [$id])}}" class="delete-tender_type text-decoration-none"><i class="btn btn-sm btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
            @endcan     
            </ul></div>')
                ->escapeColumns(['action'])
                ->make(true);
        }
        return view('tender_types.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    { 
        if ($request->ajax()) {
        
        $input = $request->only(['name']);
        $user = auth()->user();
        $input['user_id'] = $user->id; 
        

        try {
            DB::beginTransaction();
            TenderType::create($input); 
            Db::commit();

            $data = [
                "success" => true,
                "message" => "Tender Type Created Successfully"
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                "success" => false,
                "message" => "Something went worng!"
            ];
        } 
        return $data;
    }
    }
 
    public function show($id)
    { 
        
     }
 
    public function edit(Request $request, $id)
    {  
        if ($request->ajax()) {

        $tender_type = TenderType::findOrFail($id); 

        return view('tender_types.edit', compact('tender_type'));
        }
    }
 
    public function update(Request $request, $id)
    {  

        if ($request->ajax()) { 
    
            try {

                DB::beginTransaction();

                $tender_type = TenderType::findOrFail($id); 
                $tender_type->name =  $request->input('name'); 
                $tender_type->save();

                Db::commit();
    
                $data = [
                    "success" => true,
                    "message" => "Tender Type Updated Successfully"
                ];
            } catch (Exception $e) {
                DB::rollBack();
                $data = [
                    "success" => false,
                    "message" => "Something went worng!"
                ];
            } 
            return $data;
        }
 
    } 
    public function destroy($id)
    { 
        if (request()->ajax()) {
            try {
                $tender_type = TenderType::find($id);
                if (!empty($tender_type)) {
                    $tender_type->delete();
                } 
                $output = array('success' => true, 'message' => "Tender Type  is deleted successfully");
            } catch (\Exception $e) { 
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            } 
            return $output;
        }
    } 


    //pop ups data functions here
    public function create_ajax(Request $request)
    { 
        if ($request->ajax()) {

            $input = $request->only(['name']);
            $user = auth()->user();
            $input['user_id'] = $user->id;
            try {
                $tender_type = TenderType::create($input);
                $data = [
                    "success" => true,
                    "message" => "Tender type Created Successfully",
                    "tender_type" => $tender_type,
                ];
            } catch (Exception $e) {
                $data = [
                    "success" => false,
                    "'message'" => "Something went worng!"
                ];
            }
            return $data;
        }
    }
    public function getTenderTypes()
    {
        if (request()->ajax()) {
            $term = request()->input('q', '');
        
            $tender_types = TenderType::query();
        
            if (!empty($term)) {
                $tender_types->where('name', 'like', '%' . $term . '%'); 
            }
        
            $results = $tender_types->select('id', 'name as text')->get();
        
            return response()->json($results);
        }
    }

}
