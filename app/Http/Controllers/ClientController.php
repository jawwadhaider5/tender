<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Client;
use App\Models\ClientComment;
use App\Models\ClientFile;
use App\Models\ClientRespond;
use App\Models\Group;
use App\Models\Person;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Notifications\TenderRespond as NotificationsTenderRespond;
use Exception;

class ClientController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:client', ['only' => [
            'index', 'show', 'create', 'store', 'edit', 'update', 'destroy',
            'files', 'post_client_files', 'delete_files', 'comments', 'post_client_comments', 'delete_comment',
            'responds', 'post_client_responds', 'delete_respond'
        ]]);
    }

    public function index()
    {
        $user = auth()->user();
        $user->userdetail;

        $positions = Position::orderBy('name', 'desc')->get();
        $groups = Group::orderBy('name', 'desc')->get();
        $cities = City::orderBy('name', 'desc')->get();

        if (request()->ajax()) {

            $clients = Client::with('comments')->with('responds')->with('files')->get();
            $users = User::all();

            $userss = "";
            foreach ($users  as $user) {
                $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
            }


            foreach ($clients as $client) {
                $dd = '';
                $client['firstcomment'] = "No Comments yet";
                $index = 0;
                $res = '';
                $client['firstrespond'] = "No Responds yet";
                $resindex = 0;
                $fil = '';
                foreach ($client->comments as $cmd) {
                    // $dd .= '<li><a class="dropdown-item" href="#">'.$cmd->text.'</a></li>';
                    if ($index == 0) {
                        $client['firstcomment'] = $cmd->text;
                        $index++;
                    }
                    $dd .= '<div class=" m-1 p-1 border rounded">
                            <div class="row"><p ><strong class="text-success">' . $cmd->comment_by->name . '</strong> - <small>' . $cmd->created_at . '</small></p><br>
                           </div><div class="row d-flex justify-content-between"><p>' . $cmd->text . ' <br> <small><a href="/client-comment-delete/' . $cmd->id . '" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>
                            </div>
                        </div>';
                }
                foreach ($client->responds as $resp) {
                    if ($resindex == 0) {
                        $client['firstrespond'] = $resp->text;
                        $resindex++;
                    }
                    $assignedUsers = collect($resp->assigned_user_id)->map(function($uid) {
                        $u = User::find($uid);
                        return $u ? $u->name : '';
                    })->filter()->implode(', ');

                    $res .= '<div class=" m-1 p-1 border rounded">
                            <div class="row"><p ><strong class="text-primary">' . $resp->subject . '</strong> - 
                            <strong class="text-success">' . $resp->responds_by->name . '</strong> - 
                            <small>' . Carbon::parse($resp->date)->format('Y-m-d') . '</small> - 
                            <small>' . $resp->time . '</small> - 
                            <strong class="text-primary">Assigned To: </strong><strong>' . $assignedUsers . '</strong></p><br>
                           </div><div class="row d-flex justify-content-between"><p>' . $resp->text . ' <br> <small><a href="/client-respond-delete/' . $resp->id . '" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>
                            </div>
                        </div>';
                }
                foreach ($client->files as $file) {

                    $fil .= '<tr>
                                <td style="width: 80%;"><a href="' . $file->url . '" target="_blank" class="text-decoration-none">' . $file->url . '</a></td>
                                <td style="width: 20%;"><a href="/client-file-delete/' . $file->id . '" class="btn btn-sm btn-danger delete-file">Delete</a></td>
                            </tr>';
                }
                $client['comment'] = $dd;
                $client['respond'] = $res;
                $client['file'] = $fil;
                $client['allusers'] = $userss;
            }


            // return $clients;



            return Datatables::of($clients)
                ->addColumn(
                    'action',
                    '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">
              
            @can("client")
                <li class=""><a href="{{action(\'ClientController@edit\', [$id])}}" class="edit-client"><i class="btn btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
            @endcan

            @can("client")
                <li class=""><a href="{{action(\'ClientController@destroy\', [$id])}}" class="delete-client"><i class="btn btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
            @endcan     
            </ul>
            </div>'
                )
                ->addColumn(
                    'comment',
                    '<div class="dropdown p-1">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $firstcomment }}
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuButton1" style="width:500px">
                        <li><form method="POST" action="/post-client-comments">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="client_id" id="client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-9">
                                    <input type="text" name="text" id="comment-text" placeholder="Enter Your Comment..." class="form-control" required>
                                    <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary me-2 float-end" id="submit-client-form">Comment</button>
                                </div>
                            </div>
                        </div>
                    </form></li> 
                        {!! $comment !!}
                    </ul>
                    
                    </div>
                    '
                )
                ->addColumn('respond', '
                <div class="dropdown p-1">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $firstrespond }}
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuButton1" style="width:500px">
                        <li><form method="POST" action="/post-client-responds">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="client_id" id="client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-3">
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                                <div class="col-sm-2">
                                    <input type="time" name="time" class="form-control" required>
                                </div>
                                <div class="col-sm-3">
                                <select name="subject" class="form-control" id="" required>
                                    <option value="-">Select Subject</option>
                                    <option value="Subject One">Subject One</option>
                                    <option value="Subject Two">Subject Two</option>
                                    <option value="Subject Three">Subject Three</option>
                                    <option value="Subject Four">Subject Four</option>
                                </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="text" id="respond-text" placeholder="Enter Your respond..." class="form-control" required>
                                    <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                </div>
                                <div class="col-sm-2"> 
                                    <select name="assigned_user_id[]" class="form-control select2 client_response_users" multiple>
                                        {!! $allusers !!} 
                                    </select>  
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-sm btn-primary me-1 float-end" id="submit-client-form">Respond</button>
                                </div>
                            </div>
                        </div>
                    </form></li> 
                        {!! $respond !!}
                    </ul>
                    
                    </div>')
                ->addColumn('files', '
                <div class="dropdown p-1">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Files
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuButton1" style="width:500px">
                        <li><form method="POST" action="/post-client-files" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="client_id" id="client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-10 p-1">   
                                    <input type="file" name="files[]" placeholder="Select Files" class="form-control" multiple> 
                                </div>   
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary me-2 float-end" id="submit-client-form">Upload</button>
                                </div>
                            </div>
                        </div>
                    </form></li> 
                    <li><div class="row">
                    <div class="col-md-12 p-1 border rounded"> 
                        <table class="w-100">
                        {!! $file !!}
                        </table> 
                        </div>
                    </div>
                        </li>
                        
                    </ul>
                    
                    </div>')
                ->escapeColumns(['action'])
                ->make(true);
        }

        // <a href="{{action(\'ClientController@comments\', [$id])}}" class="comment-client text-decoration-none"> Comments</a>
        return view('clients.index')->with(compact('user', 'positions', 'groups', 'cities'));
    }

    public function create()
    {
        $user = auth()->user();
        $user->userdetail;
        $positions = \App\Models\Position::orderBy('name', 'desc')->get();
        $groups = \App\Models\Group::orderBy('name', 'desc')->get();
        $cities = \App\Models\City::orderBy('name', 'desc')->get();
        return view('clients.create', compact('user', 'positions', 'groups', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required',
            'contact_no' => 'required',
            'company_name' => 'required',
        ]);

        try {
            $input = $request->only(['address', 'company_name', 'contact_no', 'email', 'web_address', 'city_id', 'group_id']);

            $user = auth()->user();
            $input['user_id'] = $user->id;

            Client::create($input);

            // Redirect to clients-by-city page after successful creation
            return redirect()->route('clients-by-city')->with('success', 'Client created successfully');
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            return back()->with('error', __("messages.something_went_wrong"));
        }
    }

    public function show($id)
    {

        $user = auth()->user();
        $user->userdetail;
        $client = Client::where('id', $id)->with(['comments', 'responds', 'files'])->first();
        $positions = Position::orderBy('name', 'desc')->get();

        return view('clients.show', compact('client', 'positions'));
    }
    public function store_person(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'contact_no' => 'required',
            'position_id' => 'required',
            'client_id' => 'required',
        ]);

        try {
            $input = $request->only(['name', 'contact_no', 'email', 'position_id', 'client_id', 'linkedin']);

            if ($request->hasFile('image')) {
                $file = $request->file('image'); // Get the uploaded file

                $originName = $file->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = $fileName . '_' . time() . '.' . $extension;

                $file->move(public_path('persons/'), $fileName);

                $url = 'persons/' . $fileName;
                $input['image'] =  $url;
            }

            $user = auth()->user();
            $input['user_id'] = $user->id;

            Person::create($input);

            $output = array('success' => true, 'message' => "Client created successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
        }
        return $output;
    }

    public function comments($id)
    {
        $user = auth()->user();
        $user->userdetail;
        $client = Client::findOrFail($id);
        $comments = $client->comments;
        return view('clients.comments_modal', compact('client', 'comments'))->render();
    }

    public function post_client_comments(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $client_id = $request->get('client_id');
                $text = $request->get('text');

                ClientComment::create([
                    "client_id" => $client_id,
                    "commented_by" => $user->id,
                    "text" => $text
                ]);
                $output = array('success' => true, 'message' => "Commented successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }
            return $output;
        }
        
        // Fallback for non-AJAX requests
        try {
            $user = auth()->user();
            $client_id = $request->get('client_id');
            $text = $request->get('text');

            ClientComment::create([
                "client_id" => $client_id,
                "commented_by" => $user->id,
                "text" => $text
            ]);
            $output = array('success' => true, 'message' => "Commented successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
        }
        return redirect('/clients')->with($output);
    }

    public function delete_comment($id)
    {
        if (request()->ajax()) {
            try {
                $comment = ClientComment::find($id);
                if (!empty($comment)) {
                    $comment->delete();
                }
                $output = array('success' => true, 'message' => "Comment  is deleted successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }
            return $output;
        }
    }

    public function responds($id)
    {
        $user = auth()->user();
        $user->userdetail;
        $client = Client::findOrFail($id);
        $responds = $client->responds;
        $users = User::all();
        return view('clients.responds_modal', compact('client', 'responds', 'users'))->render();
    }

    public function post_client_responds(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $client_id = $request->get('client_id');
                $text = $request->get('text');
                $subject = $request->get('subject');
                $date = $request->get('date');
                $time = $request->get('time');
                $assigned_user_id = $request->get('assigned_user_id');

                $formattedDateTime = Carbon::createFromFormat('Y-m-d', $date)
                    ->setTimeFrom(Carbon::now());

                $client = ClientRespond::create([
                    "client_id" => $client_id,
                    "responded_by" => $user->id,
                    "subject" => $subject,
                    "date" => $formattedDateTime,
                    "time" => $time,
                    "text" => $text,
                    "assigned_user_id" => $assigned_user_id,
                    
                ]);

                $dt = $client->date->format('M, d Y H:i:s A');
                
                // Send notifications - wrapped in try-catch so failures don't break the API
                if (is_array($assigned_user_id) && !empty($assigned_user_id)) {
                    foreach ($assigned_user_id as $user_id) {
                        // Skip invalid user IDs
                        if ($user_id && $user_id != '-' && $user_id != '') {
                            try {
                                $assignedUser = User::find($user_id);
                                if ($assignedUser) {
                                    $assignedUser->notify(new NotificationsTenderRespond($dt, $user->name, $client->subject, $client->text));
                                }
                            } catch (\Throwable $notificationException) {
                                // Log notification error but don't break the API
                                Log::warning("Failed to send notification to user ID {$user_id} for client response ID {$client->id}: " . $notificationException->getMessage());
                                // Continue to next user - don't re-throw exception
                            }
                        }
                    }
                }

                $output = array('success' => true, 'message' => "Responded successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => "Something went wrong!");
            }
            return $output;
        }
        
        // Fallback for non-AJAX requests
        try {
            $user = auth()->user();
            $client_id = $request->get('client_id');
            $text = $request->get('text');
            $subject = $request->get('subject');
            $date = $request->get('date');
            $time = $request->get('time');
            $assigned_user_id = $request->get('assigned_user_id');

            $formattedDateTime = Carbon::createFromFormat('Y-m-d', $date)
                ->setTimeFrom(Carbon::now());

            $client = ClientRespond::create([
                "client_id" => $client_id,
                "responded_by" => $user->id,
                "subject" => $subject,
                "date" => $formattedDateTime,
                "time" => $time,
                "text" => $text,
                "assigned_user_id" => $assigned_user_id,
                
            ]);

            $dt = $client->date->format('M, d Y H:i:s A');
            
            // Send notifications - wrapped in try-catch so failures don't break the API
            if (is_array($assigned_user_id) && !empty($assigned_user_id)) {
                foreach ($assigned_user_id as $user_id) {
                    // Skip invalid user IDs
                    if ($user_id && $user_id != '-' && $user_id != '') {
                        try {
                            $assignedUser = User::find($user_id);
                            if ($assignedUser) {
                                $assignedUser->notify(new NotificationsTenderRespond($dt, $user->name, $client->subject, $client->text));
                            }
                        } catch (\Throwable $notificationException) {
                            // Log notification error but don't break the API
                            Log::warning("Failed to send notification to user ID {$user_id} for client response ID {$client->id}: " . $notificationException->getMessage());
                            // Continue to next user - don't re-throw exception
                        }
                    }
                }
            }

            $output = array('success' => true, 'message' => "Responded successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = array('success' => false, 'message' => "Something went wrong!");
        }
        return redirect('/clients')->with($output);
    }

    public function delete_respond($id)
    {

        if (request()->ajax()) {
            try {
                $respond = ClientRespond::find($id);
                if (!empty($respond)) {
                    $respond->delete();
                }
                $output = array('success' => true, 'message' => "Respond  is deleted successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = array('success' => false, 'message' => "Something went wrong!");
            }
            return $output;
        }
    }

    public function files($id)
    {
        $user = auth()->user();
        $user->userdetail;
        $client = Client::findOrFail($id);
        $files = $client->files;
        return view('clients.files_modal', compact('client', 'files'))->render();
    }

    public function post_client_files(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $client_id = $request->get('client_id');
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $originName = $file->getClientOriginalName();
                        $fileName = pathinfo($originName, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $fileName . '_' . time() . '.' . $extension;
                        $file->move(public_path('client-files/'), $fileName);
                        $url = 'client-files/' . $fileName;

                        ClientFile::create([
                            "client_id" => $client_id,
                            "uploaded_by" => $user->id,
                            "url" => $url
                        ]);
                    }
                }
                $output = array('success' => true, 'message' => "File uploaded successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = array('success' => false, 'message' => "Something went wrong!");
            }
            return $output;
        }
        
        // Fallback for non-AJAX requests
        try {
            $user = auth()->user();
            $client_id = $request->get('client_id');
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originName = $file->getClientOriginalName();
                    $fileName = pathinfo($originName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $fileName . '_' . time() . '.' . $extension;
                    $file->move(public_path('client-files/'), $fileName);
                    $url = 'client-files/' . $fileName;

                    ClientFile::create([
                        "client_id" => $client_id,
                        "uploaded_by" => $user->id,
                        "url" => $url
                    ]);
                }
            }
            $output = array('success' => true, 'message' => "File uploaded successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = array('success' => false, 'message' => "Something went wrong!");
        }
        return redirect('/clients')->with($output);
    }

    public function delete_file($id)
    {
        if (request()->ajax()) {
            try {
                $file = ClientFile::find($id);

                if (file_exists($file->url)) {
                    @unlink($file->url);
                }

                if (!empty($file)) {
                    $file->delete();
                }

                $output = array('success' => true, 'message' => "File is deleted successfully");
            } catch (\Exception $e) {

                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => "Something went wrong!");
            }

            return $output;
        }
    }


    public function edit($id)
    {
        $user = auth()->user();
        $user->userdetail;

        $client = Client::find($id);
        $groups = Group::orderBy('name', 'desc')->get();
        $cities = City::orderBy('name', 'desc')->get();

        return view('clients.edit_modal', compact('client', 'groups', 'cities'))->render();
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'address' => 'required',
                'contact_no' => 'required',
                'company_name' => 'required',
            ]);

            $client = Client::find($id);

            $client->address =  $request->input('address');
            $client->company_name =  $request->input('company_name');
            $client->contact_no =  $request->input('contact_no');
            $client->email =  $request->input('email');
            $client->web_address =  $request->input('web_address');
            $client->group_id =  $request->input('group_id');
            $client->city_id =  $request->input('city_id');
            $client->save();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Client updated successfully'
                ]);
            }

            return redirect("clients")->with('success', 'Client updated successfully');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while updating client'
                ]);
            }
            return redirect()->back()->with('error', 'Something went wrong while updating client');
        }
    }

    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $client = Client::find($id);
                if (!empty($client)) {
                    $client->comments()->delete();
                    $client->responds()->delete();
                    $client->files()->delete();
                    $client->persons()->delete();
                    $client->future_clients()->delete();
                    $client->tenders()->delete();
                    $client->delete();
                }

                $output = array('success' => true, 'message' => "Client  is deleted successfully");
            } catch (\Exception $e) {

                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }

            return $output;
        }
    }

    public function clientsByCity()
    {
        if (request()->ajax()) {
            $cities = City::with(['clients.group'])->get()->groupBy('code');
                
            $data = [];
            foreach ($cities as $code => $cityGroup) {
                $cityData = [];
                
                foreach ($cityGroup as $city) {
                    // Group clients by their group_id within this city
                    $clientsByGroup = $city->clients->groupBy('group_id');
                    $groups = [];
                    
                    foreach ($clientsByGroup as $groupId => $clients) {
                        $groupName = 'No Group';
                        if ($groupId && $clients->first() && $clients->first()->group) {
                            $groupName = $clients->first()->group->name;
                        }
                        
                        // Sort clients alphabetically by company name
                        $clientsArray = $clients->map(function($client) {
                            return [
                                'id' => $client->id,
                                'name' => $client->company_name
                            ];
                        })->toArray();
                        usort($clientsArray, function($a, $b) {
                            return strcasecmp($a['name'], $b['name']);
                        });
                        
                        $groups[] = [
                            'id' => $groupId ?: 'no-group',
                            'name' => $groupName,
                            'clients' => $clientsArray
                        ];
                    }
                    
                    // Sort groups alphabetically by name
                    usort($groups, function($a, $b) {
                        return strcasecmp($a['name'], $b['name']);
                    });
                    
                    $cityData[] = [
                        'id' => $city->id,
                        'name' => $city->name,
                        'groups' => $groups
                    ];
                }
                
                // Sort cities alphabetically by name
                usort($cityData, function($a, $b) {
                    return strcasecmp($a['name'], $b['name']);
                });
                
                $data[] = [
                    'code' => $code,
                    'cities' => $cityData
                ];
            }
            
            // Sort data by city code alphabetically
            usort($data, function($a, $b) {
                return strcasecmp($a['code'], $b['code']);
            });

            return response()->json(['data' => $data]);
        }

        $user = auth()->user();
        $user->userdetail;

        return view('clients.by_city');
    }

    public function edit_person($id)
    {
        $person = Person::find($id);
        $positions = Position::orderBy('name', 'desc')->get();

        return view('clients.edit_person_modal', compact('person', 'positions'))->render();
    }
    public function update_person(Request $request, $id)
    {

        if (request()->ajax()) {
            try {

                $validated = $request->validate([
                    'position_id' => 'required',
                    'contact_no' => 'required',
                    'name' => 'required',
                ]);

                $person = Person::find($id);

                if ($request->hasFile('image')) {
                    $file = $request->file('image'); // Get the uploaded file

                    $originName = $file->getClientOriginalName();
                    $fileName = pathinfo($originName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $fileName . '_' . time() . '.' . $extension;

                    $file->move(public_path('persons/'), $fileName);

                    $url = 'persons/' . $fileName;
                    $person->url =  $url;
                }


                $person->position_id =  $request->input('position_id');
                $person->name =  $request->input('name');
                $person->contact_no =  $request->input('contact_no');
                $person->email =  $request->input('email');
                $person->linkedin =  $request->input('linkedin');
                $person->save();

                $output = array('success' => true, 'message' => "Person  is updated successfully");
            } catch (\Exception $e) {

                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }

            return $output;
        }
    }
    public function delete_person($id)
    {

        if (request()->ajax()) {
            try {
                $person = Person::find($id);

                if (file_exists($person->image)) {
                    @unlink($person->image);
                }

                if (!empty($person)) {
                    $person->delete();
                }


                $person->delete();
                $output = array('success' => true, 'message' => "Person  is deleted successfully");
            } catch (\Exception $e) {

                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }

            return $output;
        }
    }

    public function getClientDetails($id)
    {
        if (request()->ajax()) {
            $client = Client::with(['comments', 'responds', 'files'])->find($id);
            
            if (!$client) {
                return response()->json(['data' => []]);
            }

            $users = User::all();
            $userss = "";
            foreach ($users as $user) {
                $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
            }

            // Process comments
            $dd = '';
            $client->firstcomment = "No Comments yet";
            $index = 0;
            foreach ($client->comments as $cmd) {
                if ($index == 0) {
                    $client->firstcomment = $cmd->text;
                    $index++;
                }
                $dd .= '<div class=" m-1 p-1 border rounded">
                        <div class="row"><p ><strong class="text-success">' . $cmd->comment_by->name . '</strong> - <small>' . $cmd->created_at . '</small></p><br>
                       </div><div class="row d-flex justify-content-between"><p>' . $cmd->text . ' <br> <small><a href="/client-comment-delete/' . $cmd->id . '" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>
                        </div>
                    </div>';
            }

            // Process responds
            $res = '';
            $client->firstrespond = "No Responds yet";
            $resindex = 0;
            foreach ($client->responds as $resp) {
                if ($resindex == 0) {
                    $client->firstrespond = $resp->text;
                    $resindex++;
                }
                $assignedUsers = collect($resp->assigned_user_id)->map(function($uid) {
                    $u = User::find($uid);
                    return $u ? $u->name : '';
                })->filter()->implode(', ');

                $res .= '<div class=" m-1 p-1 border rounded">
                        <div class="row"><p ><strong class="text-primary">' . $resp->subject . '</strong> - 
                        <strong class="text-success">' . $resp->responds_by->name . '</strong> - 
                        <small>' . Carbon::parse($resp->date)->format('Y-m-d') . '</small> - 
                        <small>' . $resp->time . '</small> - 
                        <strong class="text-primary">Assigned To: </strong><strong>' . $assignedUsers . '</strong></p><br>
                       </div><div class="row d-flex justify-content-between"><p>' . $resp->text . ' <br> <small><a href="/client-respond-delete/' . $resp->id . '" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>
                        </div>
                    </div>';
            }

            // Process files
            $fil = '';
            foreach ($client->files as $file) {
                $fil .= '<tr>
                            <td style="width: 80%;"><a href="' . $file->url . '" target="_blank" class="text-decoration-none">' . $file->url . '</a></td>
                            <td style="width: 20%;"><a href="/client-file-delete/' . $file->id . '" class="btn btn-sm btn-danger delete-file">Delete</a></td>
                        </tr>';
            }

            $client->comment = $dd;
            $client->respond = $res;
            $client->file = $fil;
            $client->allusers = $userss;

            // Generate action buttons HTML
            $actionHtml = '';
            if (auth()->user()->can('client')) {
                $editUrl = "/clients/{$client->id}/edit";
                $deleteUrl = "/clients/{$client->id}";
                $actionHtml = '<div class="btn-group">
                    <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                        data-toggle="dropdown" aria-expanded="false">Action
                        <span class="caret"></span><span class="sr-only">
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">
                        <li class=""><a href="' . $editUrl . '" class="edit-client text-decoration-none"><i class="btn btn-sm btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
                        <li class=""><a href="' . $deleteUrl . '" class="delete-client text-decoration-none"><i class="btn btn-sm btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
                    </ul>
                </div>';
            }

            return Datatables::of(collect([$client]))
                ->addColumn('action', $actionHtml)
                ->addColumn(
                    'comment',
                    '<div class="dropdown p-1">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $firstcomment }}
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuButton1" style="width:500px">
                        <li><form method="POST" action="/post-client-comments">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="client_id" id="client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-9">
                                    <input type="text" name="text" id="comment-text" placeholder="Enter Your Comment..." class="form-control" required>
                                    <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary me-2 float-end" id="submit-client-form">Comment</button>
                                </div>
                            </div>
                        </div>
                    </form></li> 
                        {!! $comment !!}
                    </ul>
                    
                    </div>
                    '
                )
                ->addColumn('respond', '
                <div class="dropdown p-1" style="position: relative;">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $firstrespond }}
                    </button>
                    <ul class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton1" style="width:650px; max-width:90vw; position: relative;">
                        <li>
                            <form method="POST" action="/post-client-responds" class="mb-2">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="client_id" id="client-id" value="{{$id}}"> 
                                <div class="row g-2 mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label small mb-1">Date</label>
                                        <input type="date" name="date" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small mb-1">Time</label>
                                        <input type="time" name="time" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small mb-1">Subject</label>
                                        <select name="subject" class="form-control form-control-sm" required>
                                            <option value="-">Select Subject</option>
                                            <option value="Subject One">Subject One</option>
                                            <option value="Subject Two">Subject Two</option>
                                            <option value="Subject Three">Subject Three</option>
                                            <option value="Subject Four">Subject Four</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-7">
                                        <label class="form-label small mb-1">Response</label>
                                        <input type="text" name="text" id="respond-text" placeholder="Enter Your respond..." class="form-control form-control-sm" required>
                                        <div id="text-error" class="alert alert-danger mt-1 p-1" style="display: none; font-size: 11px;"></div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small mb-1">Assign To</label>
                                        <div style="position: relative;">
                                            <select name="assigned_user_id[]" class="form-control form-control-sm select2 client_response_users" multiple style="width: 100% !important;">
                                                {!! $allusers !!} 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-sm btn-primary" id="submit-client-form">Respond</button>
                                    </div>
                                </div>
                            </form>
                        </li>
                        <li class="divider" style="border-top: 1px solid #ddd; margin: 8px 0;"></li>
                        <li>
                            <div class="px-2" style="max-height: 300px; overflow-y: auto;">{!! $respond !!}</div>
                        </li>
                    </ul>
                </div>')
                ->addColumn('files', '
                <div class="dropdown p-1">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Files
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuButton1" style="width:500px">
                        <li><form method="POST" action="/post-client-files" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="client_id" id="client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-10 p-1">   
                                    <input type="file" name="files[]" placeholder="Select Files" class="form-control" multiple> 
                                </div>   
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary me-2 float-end" id="submit-client-form">Upload</button>
                                </div>
                            </div>
                        </div>
                    </form></li> 
                    <li><div class="row">
                    <div class="col-md-12 p-1 border rounded"> 
                        <table class="w-100">
                        {!! $file !!}
                        </table> 
                        </div>
                    </div>
                        </li>
                        
                    </ul>
                    
                    </div>')
                ->escapeColumns(['action'])
                ->make(true);
        }

        return response()->json(['data' => []]);
    }
}
