<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;  
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{ 
    function __construct()
    {
        $this->middleware('permission:city', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]); 
    }

    public function index()
    {    

        if (request()->ajax()) {

            $city = City::all(); 

            return Datatables::of($city)
                ->addColumn(
                    'action',
                    '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button> 
            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">  
            @can("city")
                <li><a href="{{action(\'CityController@edit\', [$id])}}"  class="edit-city text-decoration-none"><i class=" btn btn-sm btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
            @endcan 
            @can("city")
                <li class=""><a href="{{action(\'CityController@destroy\', [$id])}}" class="delete-city text-decoration-none"><i class="btn btn-sm btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
            @endcan     
            </ul></div>')
                ->escapeColumns(['action'])
                ->make(true);
        }
        return view('cities.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    { 
        if ($request->ajax()) {
        
        $input = $request->only(['name', 'code']);
        $user = auth()->user();
        $input['user_id'] = $user->id; 
        

        try {
            DB::beginTransaction();
            City::create($input); 
            Db::commit();

            $data = [
                "success" => true,
                "'message'" => "City Created Successfully"
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

        $city = City::findOrFail($id); 

        return view('cities.edit', compact('city'));
        }
    }
 
    public function update(Request $request, $id)
    {  
        if ($request->ajax()) { 
    
            try {

                DB::beginTransaction();

                $city = City::findOrFail($id); 
                $city->name =  $request->input('name'); 
                $city->code =  $request->input('code');  
                $city->save();

                Db::commit();
    
                $data = [
                    "success" => true,
                    "'message'" => "City Updated Successfully"
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
                $position = City::find($id);
                if (!empty($position)) {
                    $position->delete();
                } 
                $output = array('success' => true, 'message' => "City  is deleted successfully");
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

            $input = $request->only(['name', 'code']);
            $user = auth()->user();
            $input['user_id'] = $user->id;
            try {
                $city = City::create($input);
                $data = [
                    "success" => true,
                    "message" => "City Created Successfully",
                    "city" => $city,
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
    public function getCities()
    {
        if (request()->ajax()) {
            $term = request()->input('q', '');
        
            $cities = City::query();
        
            if (!empty($term)) {
                $cities->where('name', 'like', '%' . $term . '%');
                $cities->orWhere('code', 'like', '%' . $term . '%');
            }
        
            $results = $cities->select('id', 'name as text' , 'code')->get();
        
            return response()->json($results);
        }
    }
}
