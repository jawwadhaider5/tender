<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; 
use App\Models\UserDetail;   
use Illuminate\Support\Facades\Log;


// new push 

class UserDetailController extends Controller
{
     

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $user = auth()->user();
        $userdetail = $user->userdetail; 

        if (request()->ajax()) {

            // $userdetail = DB::select("SELECT u.*,COUNT(t.id) AS totalticket, (tt.payment_amount) AS totalamount,SUM(pp.received_amount) As paid ,((tt.payment_amount)- SUM(pp.received_amount)) As dueamount
            // FROM userdetails AS u
            // LEFT JOIN businessdetails AS b
            // ON b.userdetail_id = u.id
            // LEFT JOIN users AS us
            // ON us.userdetail_id = u.id
            // GROUP BY u.id");

                $userdetail = UserDetail::leftjoin('business_details', 'user_details.business_detail_id', '=', 'business_details.id')
                ->leftjoin('users', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.user_id', '=', auth()->user()->id)
                ->select('user_details.*','users.name as uname', 'users.email as uemail')->get();

                //    echo json_encode($userdetail);


            return Datatables::of($userdetail)
            ->addColumn('action', 
            '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">

                <li class=""><a href="{{action(\'UserDetailController@show\', [$id])}}" class="view-userdetail"><i class="btn btn-info  mdi mdi-eye p-2" title="View"></i></a></li>
            
                <li class=""><a href="{{action(\'UserDetailController@edit\', [$id])}}"><i class="btn btn-dark mdi mdi-table-edit p-2 mt-1 mb-1" title="Edit"></i></a></li>
            
                <li class=""><a href="{{action(\'UserDetailController@destroy\', [$id])}}" class="delete-userdetail"><i class="btn btn-danger  mdi mdi-delete p-2" title="Delete"></i></a></li> 
            </ul>
            </div>'

        )
        ->escapeColumns(['action'])
        ->make(true);

        }

        
        return view('user_details.index')->with(compact('userdetail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $user = auth()->user();
        $userdetail = $user->userdetail; 


        return view('user_details.create')->with(['user' => $user , 'userdetail' => $userdetail]);
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

        
        $validated = $request->validate([
            'gender' => 'required',
            'phone_no_one' => 'required',
            'address_one' => 'required',
            'account_type' => 'required',


        ]);

    
          

        $input = $request->only([ 
         'gender' ,'date_of_birth','cnic_number','passport_number','phone_no_one','phone_no_two','address_one','address_two','account_type','joining_date','leaving_date','salary_per_month']);

        if($request->hasFile('image')) {
            $originName = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('image')->move(public_path('open/images/userdetail-images/'), $fileName); 
            $url = 'open/images/userdetail-images/'.$fileName; 
            $input['image'] = $url;
        } 


        $user = auth()->user(); 
        $input['user_id'] = $user->id; 

     

        UserDetail::create($input);


        return redirect("user-detail");
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

        // $user = auth()->user();
        // $userdetail = $user->userdetail; 

        $userdetail =UserDetail::find($id);

         
        // return json_encode($userdetail);

         return view ('user_details.show')->with(compact('userdetail'));
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

        $user = auth()->user();
        // $userdetail = $user->userdetail; 

        $userdetail =UserDetail::find($id);

         
        // return json_encode($userdetail);

         return view ('user_details.edit')->with(compact('userdetail','user'));
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

        $validated = $request->validate([
            
            'gender' => 'required',
            'phone_no_one' => 'required',
            'address_one' => 'required',
            'account_type' => 'required',

        ]); 

        $userdetail=UserDetail::find($id);

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

        return redirect("user-detail") ->with(compact('userdetail'));
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

        if (request()->ajax()) {
            try {
                    $userdetail = UserDetail::find($id);

                    if(!empty($userdetail)){
                        
                        $userdetail->delete();

                    }

                    $output = array('success' => true, 'message' => "User Detail is deleted successfully");

            } catch(\Exception $e){
                
                Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }

            return $output;
        }
    }






}
