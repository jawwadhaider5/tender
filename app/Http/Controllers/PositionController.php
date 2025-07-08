<?php

namespace App\Http\Controllers;
 
use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;  
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PositionController extends Controller
{ 
    function __construct()
    {
        $this->middleware('permission:position', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]); 
    }
    public function index()
    {    

        if (request()->ajax()) {

            $position = Position::all(); 

            return Datatables::of($position)
                ->addColumn(
                    'action',
                    '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button> 
            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">  
            @can("position")
                <li><a href="{{action(\'PositionController@edit\', [$id])}}"  class="edit-position text-decoration-none"><i class=" btn btn-sm btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
            @endcan 
            @can("position")
                <li class=""><a href="{{action(\'PositionController@destroy\', [$id])}}" class="delete-position text-decoration-none"><i class="btn btn-sm btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
            @endcan     
            </ul></div>')
                ->escapeColumns(['action'])
                ->make(true);
        }
        return view('positions.index');
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
            Position::create($input); 
            Db::commit();

            $data = [
                "success" => true,
                "'message'" => "Position Created Successfully"
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                "success" => false,
                "'message'" => "Something went worng!"
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

        $position = Position::findOrFail($id); 

        return view('positions.edit', compact('position'));
        }
    }
 
    public function update(Request $request, $id)
    {  
        if ($request->ajax()) { 
    
            try {

                DB::beginTransaction();

                $position = Position::findOrFail($id); 
                $position->name =  $request->input('name');  
                $position->save();

                Db::commit();
    
                $data = [
                    "success" => true,
                    "'message'" => "Position Updated Successfully"
                ];
            } catch (Exception $e) {
                DB::rollBack();
                $data = [
                    "success" => false,
                    "'message'" => "Something went worng!"
                ];
            } 
            return $data;
        }
 
    } 
    public function destroy($id)
    { 
        if (request()->ajax()) {
            try {
                $position = Position::find($id);
                if (!empty($position)) {
                    $position->delete();
                } 
                $output = array('success' => true, 'message' => "Position  is deleted successfully");
            } catch (\Exception $e) { 
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            } 
            return $output;
        }
    } 

    public function searchPositions(Request $request) {
        $query = $request->input('query');
        $positions = Position::where('name', 'LIKE', "%{$query}%")->get();
        return response()->json($positions);
    }
     
    public function createPosition(Request $request) {
        $position = Position::firstOrCreate(['name' => $request->name]);
        return response()->json($position);
    }

    //pop ups data functions here
    public function create_ajax(Request $request)
    { 
        if ($request->ajax()) {

            $input = $request->only(['name', 'code']);
            $user = auth()->user();
            $input['user_id'] = $user->id;
            try {
                $position = Position::create($input);
                $data = [
                    "success" => true,
                    "message" => "Position Created Successfully",
                    "position" => $position,
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
    public function getPositions()
    {
        if (request()->ajax()) {
            $term = request()->input('q', ''); 
            $positions = Position::query(); 
            if (!empty($term)) {
                $positions->where('name', 'like', '%' . $term . '%'); 
            } 
            $results = $positions->select('id', 'name as text')->get(); 
            return response()->json($results);
        }
    }
}
