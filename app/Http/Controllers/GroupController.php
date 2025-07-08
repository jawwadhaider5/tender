<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;  
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{ 

    function __construct()
    {
        $this->middleware('permission:group', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]); 
    }

    public function index()
    {    

        if (request()->ajax()) {

            $group = Group::all(); 

            return Datatables::of($group)
                ->addColumn(
                    'action',
                    '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button> 
            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">  
            @can("group")
                <li><a href="{{action(\'GroupController@edit\', [$id])}}"  class="edit-group text-decoration-none"><i class=" btn btn-sm btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
            @endcan 
            @can("group")
                <li class=""><a href="{{action(\'GroupController@destroy\', [$id])}}" class="delete-group text-decoration-none"><i class="btn btn-sm btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
            @endcan     
            </ul></div>')
                ->escapeColumns(['action'])
                ->make(true);
        }
        return view('groups.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
         

        if ($request->ajax()) {
        
        $input = $request->only(['name', 'tax']);
        $user = auth()->user();
        $input['user_id'] = $user->id; 
        

        try {
            DB::beginTransaction();
            Group::create($input); 
            Db::commit();

            $data = [
                "success" => true,
                "'message'" => "Group Created Successfully"
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

        $group = Group::findOrFail($id); 

        return view('groups.edit', compact('group'));
        }
    }
 
    public function update(Request $request, $id)
    { 
       

        if ($request->ajax()) { 
    
            try {

                DB::beginTransaction();

                $group = Group::findOrFail($id); 
                $group->name =  $request->input('name');  
                $group->save();

                Db::commit();
    
                $data = [
                    "success" => true,
                    "'message'" => "Group Updated Successfully"
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
                $group = Group::find($id);
                if (!empty($group)) {
                    $group->delete();
                } 
                $output = array('success' => true, 'message' => "Group  is deleted successfully");
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
                $group = Group::create($input);
                $data = [
                    "success" => true,
                    "message" => "Group Created Successfully",
                    "group" => $group,
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
    public function getGroups()
    {
        if (request()->ajax()) {
            $term = request()->input('q', '');
        
            $groups = Group::query();
        
            if (!empty($term)) {
                $groups->where('name', 'like', '%' . $term . '%');
            }
        
            $results = $groups->select('id', 'name as text')->get();
        
            return response()->json($results);
        }
    }

}
