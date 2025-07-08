<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use App\Models\User; 
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\UserDetail;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $userdetai = $user->userdetail; 

        $data = User::orderBy('id', 'DESC')->get(); 
        return view('users.index', compact('data', 'user'))->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        if (!auth()->user()->can('user-create')) {
            abort(403, 'Unauthorized action.');
        }
 

        $user = auth()->user();
        $userdetai = $user->userdetail; 

        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles', 'user' ));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('user-create')) {
            abort(403, 'Unauthorized action.');
        }
  

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'gender' => 'required',
            'phone_no_one' => 'required',
            'address_one' => 'required', 
        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        try {
            DB::beginTransaction();
            $user = User::create($input);
            if (!empty($user->id) || $user->id != null) {

                $user->assignRole($request->input('roles'));
                $url = null;

                if ($request->hasFile('image')) {

                    $originName = $request->file('image')->getClientOriginalName();
                    $fileName = pathinfo($originName, PATHINFO_FILENAME);
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $fileName = $fileName . '_' . time() . '.' . $extension;
                    $request->file('image')->move(public_path('open/images/userdetail-images/'), $fileName);
                    $url = 'open/images/userdetail-images/' . $fileName;   
                }

                $data = [
                    'user_id' =>  $user->id, 
                    "gender" => $request->get("gender"),
                    "date_of_birth" => $request->get("date_of_birth"),
                    "image" => $url, 
                    "phone_no_one" => $request->get("phone_no_one"), 
                    "address_one" => $request->get("address_one"), 
                ];
               UserDetail::create($data); 
 
 
                

            }

            DB::commit(); 

        } catch (Exception $e) {
            DB::rollback();
        }
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    public function show($id)
    {
        if (!auth()->user()->can('user-view')) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::find($id);
        $userdetail = $user->userdetail;
 
        
        return view('users.show', compact('user'));
    }
    public function edit($id)
    {
        $user = auth()->user();
        $userdetai = $user->userdetail;
        if (!auth()->user()->can('user-edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        $users = User::find($id);
        $userdetails = $users->userdetail;
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $users->roles->pluck('name', 'name')->all(); 
        return view('users.edit', compact('users', 'roles', 'userRole', 'userdetai',  'userdetails', 'user'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('user-edit')) {
            abort(403, 'Unauthorized action.');
        }
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required',
            'gender' => 'required',
            'phone_no_one' => 'required',
            'address_one' => 'required', 
        ]);

        $input = $request->all();
        if (!empty($input['password'])) { 
            $input['password'] = bcrypt($input['password']);
        } 
        try {

            DB::beginTransaction();
            $user = User::find($id);
            $user->update($input);
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
            $userdetail = $user->userdetail;
            if ($request->hasFile('image')) {
                if (file_exists($userdetail->image)) {
                    @unlink($userdetail->image);
                }

                $originName = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = $fileName . '_' . time() . '.' . $extension;
                $request->file('image')->move(public_path('open/images/userdetail-images/'), $fileName);
                $url = 'open/images/userdetail-images/' . $fileName;
                $userdetail->image = $url;
            }


            $userdetail->gender =  $request->input('gender');
            $userdetail->date_of_birth =  $request->input('date_of_birth'); 
            $userdetail->phone_no_one =  $request->input('phone_no_one'); 
            $userdetail->address_one =  $request->input('address_one'); 
            $userdetail->save();
            DB::commit();

            return redirect()->route('users.index')
            ->with('success', 'User Updated successfully');

        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route('users.index')
            ->with('success', 'Something Went Wrong!'); 
        }
    }
    public function password(Request $request, $id)
    {
        if (!auth()->user()->can('user-edit')) {
            abort(403, 'Unauthorized action.');
        }
        $this->validate($request, [
            'password' => 'same:confirm-password',
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            // $input['password'] = Hash::make($input['password']);
            $input['password'] = bcrypt($input['password']);
        } 
        else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);

        return redirect()->route('users.index')->with('success', 'Password updated successfully');
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('user-delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $user = User::find($id);
                
                if (!empty($user)) {
                    $userdetail = $user->userdetail;
                    $customer_lines = $user->customer_lines;
                    if ($userdetail) {
                        $userdetail->delete();
                    }
                    if ($customer_lines) {
                        $user->customer_lines()->delete();
                    }
                    
                    $user->delete(); 
                    $output = array('success' => true, 'message' => "User is deleted successfully");
                }else{
                    $output = array('success' => true, 'message' => "Something Went Wrong!");
                }
                
            } catch (\Exception $e) {
                $output = array('success' => false, 'message' => "Something Went Wrong!" . $e);
            }

            return $output;
        }
    }


 


    public function suppliersearch(Request $request)
    {
        $suppliers = [];

        if ($request->has('q')) {
            $search = $request->q;

            $suppliers = User::leftJoin('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.account_type', 'Supplier')
                ->where('users.name', 'LIKE', "%$search%")
                ->select('users.id', 'users.name', 'users.email')->get();
        }
        return response()->json($suppliers);
    }
    public function search(Request $request)
    {

        $output = "";
        $items = User::all();
        $items = $items->where('name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%');
        foreach ($items as $item) {
            $output .=
                '<tr> 
                    <td>' . $item->name . '</td>
                    <td>' . $item->email . '</td> 
                </tr>';
        }

        return response($output); 
    }
}
