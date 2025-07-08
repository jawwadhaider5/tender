<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Models\User; 
use Illuminate\Support\Arr; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{ 
    public function index()
    {
        //
        
        $user = auth()->user();
        $userdetai = $user->userdetail; 
        
        if (!auth()->user()->can('userprofile-edit')) {
            abort(403, 'Unauthorized action.');
        }

        $userdetails = $user->userdetail;
    
        return view('userprofile.profile',compact('user','userdetai','userdetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        

         //
         if (!auth()->user()->can('userprofile-edit')) {
            abort(403, 'Unauthorized action.');
        }


        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            // 'password' => 'same:confirm-password',
            // 'roles' => 'required',
            'gender' => 'required',
            'phone_no_one' => 'required',
            'address_one' => 'required',
            // 'account_type' => 'required',
        ]);
    
        $input = $request->all();
            
        // $input = $request->only([ 'name' ,'email','status']);
      
    
    
   
        try {

            DB::beginTransaction();
            $user = User::find($id);
            $user->update($input);

        //    echo json_encode($user);

            // DB::table('model_has_roles')->where('model_id',$id)->delete();
            // $user->assignRole($request->input('roles'));

           
           

            // echo json_encode($user);
            
            
                

                $userdetail= $user->userdetail;

                // $userdetail=UserDetail::find($id);

                if($request->hasFile('image')) {
        
                    
                    if(file_exists($userdetail->image)){
                        @unlink($userdetail->image);
                    }
        
                    $originName = $request->file('image')->getClientOriginalName();
                    $fileName = pathinfo($originName, PATHINFO_FILENAME);
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $fileName = $fileName.'_'.time().'.'.$extension;
                    $request->file('image')->move(public_path('open/images/userdetail-images/'), $fileName); 
                    $url = 'open/images/userdetail-images/'.$fileName; 
                    $userdetail->image = $url;
                }
                

                $userdetail->gender=  $request->input('gender');
                $userdetail->date_of_birth =  $request->input('date_of_birth');
                $userdetail->cnic_number =  $request->input('cnic_number');
                $userdetail->passport_number =  $request->input('passport_number');
                $userdetail->phone_no_one =  $request->input('phone_no_one');
                $userdetail->phone_no_two =  $request->input('phone_no_two');
                $userdetail->address_one =  $request->input('address_one');
                $userdetail->address_two =  $request->input('address_two');
                $userdetail->account_type =  $request->input('account_type');
                $userdetail->joining_date =  $request->input('joining_date');
                $userdetail->leaving_date =  $request->input('leaving_date');
                $userdetail->salary_per_month =  $request->input('salary_per_month');
                
            
                $userdetail->save();  
   

          
   
        //    echo json_encode($userdetail);
            
   
    

            DB::commit();
        } catch (\Throwable $th) {

            DB::rollback();


        }

        return redirect()->route('userprofile.index')
        ->with('success','User Profile updated successfully');
    }

        // *** Update password and confirm password

        public function password(Request $request, $id){
         
            if (!auth()->user()->can('userprofile-edit')) {
                abort(403, 'Unauthorized action.');
            }
    
    
            $this->validate($request, [
               
                'password' => 'same:confirm-password',
               
            ]);
        
            // $input = $request->all();

            
        $input = $request->only(['password']);

            if(!empty($input['password'])){ 
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = Arr::except($input,array('password'));    
            }

            $user = User::find($id);
            $user->update($input);

            
    
            return redirect()->route('userprofile.index')
            ->with('success','Password updated successfully');
    
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
