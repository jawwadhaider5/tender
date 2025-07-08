<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Client;
use App\Models\FutureClient;
use App\Models\FutureClientComment;
use App\Models\FutureClientFile;
use App\Models\FutureClientRespond;
use App\Models\TenderType;
use App\Models\User;
use App\Notifications\TenderRespond as NotificationsTenderRespond;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class FutureClientController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:future-client', ['only' => [
            'index', 'show', 'create', 'store', 'edit', 'update', 'destroy',
            'files', 'post_future_client_files', 'delete_files', 'comments', 'post_future_client_comments', 'delete_comment',
            'responds', 'post_future_client_responds', 'delete_respond'
        ]]);
    }

    public function index()
    {
        $user = auth()->user();
        $user->userdetail;

        $tender_types = TenderType::orderBy('name', 'asc')->get();
        $clients = Client::orderBy('company_name', 'asc')->get();

        $users = User::all(); 
            $userss = "";
            foreach ($users  as $user) {
                $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
            }


        if (request()->ajax()) {
            $future_client = FutureClient:: 
                with('comments')->with('responds')->with('files')->with('client')->with('tender_type') 
                ->get(); 
 
            $users = User::all(); 
            $userss = "";
            foreach ($users  as $user) {
                $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
            }
 
            foreach ($future_client as $client) {
                $dd = '';
                $client['firstcomment'] = "No Comments yet";
                $index = 0;
                $res = '';
                $client['firstrespond'] = "No Responds yet";
                $resindex = 0;
                $fil = '';
                foreach ($client->comments as $cmd) {
                    
                    if ($index == 0) {
                        $client['firstcomment'] = $cmd->text;
                        $index++;
                    }
                    $dd .= '<div class=" m-1 p-1 border rounded">
                            <div class="row"><p ><strong class="text-success">' . $cmd->comment_by->name . '</strong> - <small>' . $cmd->created_at . '</small></p><br>
                           </div><div class="row d-flex justify-content-between"><p>' . $cmd->text . ' <br> <small><a href="/future-client-comment-delete/' . $cmd->id . '" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>
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
                            <strong class="text-primary">Assigned To: </strong><strong>' . $assignedUsers . '</strong></p><br>
                           </div><div class="row d-flex justify-content-between"><p>' . $resp->text . ' <br> <small><a href="/future-client-respond-delete/' . $resp->id . '" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>
                            </div>
                        </div>';
                }
                foreach ($client->files as $file) {

                    $fil .= '<tr>
                                <td style="width: 80%;"><a href="' . $file->url . '" target="_blank" class="text-decoration-none">' . $file->url . '</a></td>
                                <td style="width: 20%;"><a href="/future-client-file-delete/' . $file->id . '" class="btn btn-sm btn-danger delete-file">Delete</a></td>
                            </tr>';
                }
                $client['comment'] = $dd;
                $client['respond'] = $res;
                $client['file'] = $fil;
                $client['allusers'] = $userss;
            }

            $datatable = Datatables::of($future_client)
                ->addColumn(
                    'action',
                    '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">
              
            @can("future-client")
                <li class=""><a href="{{action(\'FutureClientController@edit\', [$id])}}" class="edit-future-client"><i class="btn btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
            @endcan

            @can("future-client")
                <li class=""><a href="{{action(\'FutureClientController@destroy\', [$id])}}" class="delete-future-client"><i class="btn btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
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
                        <li><form method="POST" action="/post-future-client-comments">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="future_client_id" id="client-id" value="{{$id}}"> 
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
                        <li><form method="POST" action="/post-future-client-responds">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="future_client_id" id="future-client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-3">
                                    <input type="date" name="date" class="form-control" required>
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
                                    <select name="assigned_user_id[]" class="form-control select2 future_client_response_users" multiple>
                                        {!! $allusers !!} 
                                    </select>  
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-sm btn-primary me-1 float-end" id="submit-future-client-form">Respond</button>
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
                        <li><form method="POST" action="/post-future-client-files" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="future_client_id" id="client-id" value="{{$id}}"> 
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
                ->escapeColumns(['action']);

            if (request()->has('highlight')) {
                $datatable->setRowClass(function ($row) {
                    return $row->id == request('highlight') ? 'highlight-row' : '';
                });
            }

            return $datatable->make(true);
        }

        return view('future_clients.index', compact('user', 'tender_types', 'clients', 'userss'));
    }

    public function create()
    {
        $clients = Client::orderBy('company_name', 'asc')->get();
        $tender_types = TenderType::orderBy('name', 'asc')->get();
        $users = User::all();
        $userss = "";
        foreach ($users as $user) {
            $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }
        return view('future_clients.create', compact('clients', 'tender_types', 'userss'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required',
            'tender_type_id' => 'required',
            'description' => 'required'
        ]);

        $input = $request->only([
            'client_id', 'tender_type_id', 'description', 'assigned_number',
            'start_date', 'coming_date', 'period', 'term', 'amount', 'user_id'
        ]);

        $input['start_date'] = Carbon::createFromFormat('Y-m-d', $input['start_date'])->setTimeFrom(Carbon::now());
        $input['coming_date'] = Carbon::createFromFormat('Y-m-d', $input['coming_date'])->setTimeFrom(Carbon::now());

        try {
            FutureClient::create($input);
            $output = array('success' => true, 'message' => "Future Client created successfully");
            // Redirect to by_city page after successful creation
            return redirect()->route('future-clients-by-city')->with('success', 'Future Client created successfully');
        } catch (Exception $e) {
            $output = array('success' => false, 'message' => "Something went wrong!");
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function show($id)
    {

        $user = auth()->user();
        $user->userdetail;
        $future_client = FutureClient::where('id', $id)->with(['comments', 'responds', 'files', 'tender_type','user'])->first(); 

        return view('future_clients.show', compact('future_client' ));
    }

    public function comments($id)
    {
        $user = auth()->user();
        $user->userdetail;
        $future_client = FutureClient::findOrFail($id);
        $comments = $future_client->comments;
        return view('future_clients.comments_modal', compact('future_client', 'comments'))->render();
    }

    public function post_future_client_comments(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $future_client_id = $request->get('future_client_id');
                $text = $request->get('text');

                FutureClientComment::create([
                    "future_client_id" => $future_client_id,
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
            $future_client_id = $request->get('future_client_id');
            $text = $request->get('text');

            FutureClientComment::create([
                "future_client_id" => $future_client_id,
                "commented_by" => $user->id,
                "text" => $text
            ]);
            $output = array('success' => true, 'message' => "Commented successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
        }
        return redirect('/future-clients')->with($output);
    }

    public function delete_comment($id)
    {
        if (request()->ajax()) {
            try {
                $comment = FutureClientComment::find($id);
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
        $future_client = FutureClient::findOrFail($id);
        $responds = $future_client->responds;
        $users = User::all();
        return view('future_clients.responds_modal', compact('future_client', 'responds', 'users'))->render();
    }

    public function post_future_client_responds(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $future_client_id = $request->get('future_client_id');
                $text = $request->get('text');
                $subject = $request->get('subject');
                $date = $request->get('date');
                $time = $request->get('time');
                $assigned_user_id = $request->get('assigned_user_id');
                $formattedDateTime = Carbon::createFromFormat('Y-m-d', $date)
                    ->setTimeFrom(Carbon::now());

                $future_client = FutureClientRespond::create([
                    "future_client_id" => $future_client_id,
                    "responded_by" => $user->id,
                    "subject" => $subject,
                    "assigned_user_id" => $assigned_user_id,
                    "date" => $formattedDateTime,
                    "time" => $time,
                    "text" => $text,
                ]);

                $dt = $future_client->date->format('M, d Y H:i:s A');

                // Send notifications to all assigned users
                if (is_array($assigned_user_id)) {
                    foreach ($assigned_user_id as $userId) {
                        if ($userId && $userId != '-') {
                            User::find($userId)->notify(new NotificationsTenderRespond($dt, $user->name, $future_client->subject, $future_client->text));
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
            $future_client_id = $request->get('future_client_id');
            $text = $request->get('text');
            $subject = $request->get('subject');
            $date = $request->get('date');
            $time = $request->get('time');
            $assigned_user_id = $request->get('assigned_user_id');
            $formattedDateTime = Carbon::createFromFormat('Y-m-d', $date)
                ->setTimeFrom(Carbon::now());

            $future_client = FutureClientRespond::create([
                "future_client_id" => $future_client_id,
                "responded_by" => $user->id,
                "subject" => $subject,
                "assigned_user_id" => $assigned_user_id,
                "date" => $formattedDateTime,
                "time" => $time,
                "text" => $text,
            ]);

            $dt = $future_client->date->format('M, d Y H:i:s A');

            // Send notifications to all assigned users
            if (is_array($assigned_user_id)) {
                foreach ($assigned_user_id as $userId) {
                    if ($userId && $userId != '-') {
                        User::find($userId)->notify(new NotificationsTenderRespond($dt, $user->name, $future_client->subject, $future_client->text));
                    }
                }
            }

            $output = array('success' => true, 'message' => "Responded successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = array('success' => false, 'message' => "Something went wrong!");
        }
        return redirect('/future-clients')->with($output);
    }

    public function delete_respond($id)
    {

        if (request()->ajax()) {
            try {
                $respond = FutureClientRespond::find($id);
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

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function files($id)
    {
        $user = auth()->user();
        $user->userdetail;
        $future_client = FutureClient::findOrFail($id);
        $files = $future_client->files;
        return view('future_clients.files_modal', compact('future_client', 'files'))->render();
    }

    public function post_future_client_files(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $future_client_id = $request->get('future_client_id');
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $originName = $file->getClientOriginalName();
                        $fileName = pathinfo($originName, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $fileName . '_' . time() . '.' . $extension;
                        $file->move(public_path('future-client-files/'), $fileName);
                        $url = 'future-client-files/' . $fileName;

                        FutureClientFile::create([
                            "future_client_id" => $future_client_id,
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
            $future_client_id = $request->get('future_client_id');
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originName = $file->getClientOriginalName();
                    $fileName = pathinfo($originName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $fileName . '_' . time() . '.' . $extension;
                    $file->move(public_path('future-client-files/'), $fileName);
                    $url = 'future-client-files/' . $fileName;

                    FutureClientFile::create([
                        "future_client_id" => $future_client_id,
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
        return redirect('/future-clients')->with($output);
    }

    public function delete_file($id)
    {
        if (request()->ajax()) {
            try {
                $file = FutureClientFile::find($id);

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

        $future_client = FutureClient::find($id);
        $tender_types = TenderType::orderBy('name', 'asc')->get();
        $clients = Client::orderBy('company_name', 'asc')->get();

        $future_client['start_date'] = Carbon::parse($future_client['start_date'])->format('Y-m-d');
        $future_client['coming_date'] = Carbon::parse($future_client['coming_date'])->format('Y-m-d');

        return view('future_clients.edit_modal', compact('future_client', 'tender_types', 'clients'))->render();
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'client_id' => 'required',
            'tender_type_id' => 'required',
            'description' => 'required',
        ]);

        $input = $request->only([
            'client_id', 'tender_type_id', 'description', 'assigned_number',
            'start_date', 'coming_date', 'period', 'term', 'amount'
        ]);

        $input['start_date'] = Carbon::createFromFormat('Y-m-d', $input['start_date'])->setTimeFrom(Carbon::now());
        $input['coming_date'] = Carbon::createFromFormat('Y-m-d', $input['coming_date'])->setTimeFrom(Carbon::now());

        try {
            $future_client = FutureClient::find($id);

            $future_client->client_id =  $request->input('client_id');
            $future_client->tender_type_id =  $request->input('tender_type_id');
            $future_client->description =  $request->input('description');
            $future_client->assigned_number =  $request->input('assigned_number');
            $future_client->period =  $request->input('period');
            $future_client->term =  $request->input('term');
            $future_client->amount =  $request->input('amount');
            $future_client->start_date =  Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->setTimeFrom(Carbon::now());
            $future_client->coming_date =  Carbon::createFromFormat('Y-m-d', $request->input('coming_date'))->setTimeFrom(Carbon::now());
            $future_client->save();

            $data = ["success" => true,  "'message'" => "Future Client updated successfully"];
        } catch (Exception $e) {
            $data = ["success" => false,  "'message'" => "Something went worng!"];
        }

        return $data;

        // return redirect("/future-clients")->with('success', 'Future Client  updated successfully');
    }

    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $future_client = FutureClient::find($id);
                if (!empty($future_client)) {
                    $future_client->comments()->delete();
                    $future_client->responds()->delete();
                    $future_client->files()->delete();
                    $future_client->delete();
                }

                $output = array('success' => true, 'message' => "Future Client  is deleted successfully");
            } catch (\Exception $e) {

                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }

            return $output;
        }
    }

    public function futureClientsByCity()
    {
        if (request()->ajax()) {
            $cities = City::with(['clients' => function($query) {
                $query->select('clients.id', 'clients.company_name', 'clients.city_id')
                      ->join('future_clients', 'clients.id', '=', 'future_clients.client_id')
                      ->select('clients.id', 'clients.company_name', 'clients.city_id', 'future_clients.id as future_client_id');
            }])
            ->select('id', 'name', 'code')
            ->get()
            ->groupBy('code')
            ->map(function($cities) {
                return [
                    'code' => $cities->first()->code,
                    'cities' => $cities->map(function($city) {
                        return [
                            'id' => $city->id,
                            'name' => $city->name,
                            'clients' => $city->clients->map(function($client) {
                                return [
                                    'id' => $client->future_client_id,
                                    'name' => $client->company_name
                                ];
                            })
                        ];
                    })
                ];
            })->values();

            return response()->json(['data' => $cities]);
        }

        // Pass required variables for the modal form
        $clients = Client::orderBy('company_name', 'asc')->get();
        $tender_types = TenderType::orderBy('name', 'asc')->get();
        $users = User::all();
        $userss = "";
        foreach ($users as $user) {
            $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }

        return view('future_clients.by_city', compact('clients', 'tender_types', 'userss'));
    }

    public function getFutureClientDetails($id)
    {
        if (request()->ajax()) {
            $future_client = FutureClient::with(['comments', 'responds', 'files', 'client'])->find($id);
            
            if (!$future_client) {
                return response()->json(['data' => []]);
            }

            $users = User::all();
            $userss = "";
            foreach ($users as $user) {
                $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
            }

            // Process comments
            $dd = '';
            $future_client->firstcomment = "No Comments yet";
            $index = 0;
            foreach ($future_client->comments as $cmd) {
                if ($index == 0) {
                    $future_client->firstcomment = $cmd->text;
                    $index++;
                }
                $dd .= '<div class=" m-1 p-1 border rounded">
                        <div class="row"><p ><strong class="text-success">' . $cmd->comment_by->name . '</strong> - <small>' . $cmd->created_at . '</small></p><br>
                       </div><div class="row d-flex justify-content-between"><p>' . $cmd->text . ' <br> <small><a href="/future-client-comment-delete/' . $cmd->id . '" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>
                        </div>
                    </div>';
            }

            // Process responds
            $res = '';
            $future_client->firstrespond = "No Responds yet";
            $resindex = 0;
            foreach ($future_client->responds as $resp) {
                if ($resindex == 0) {
                    $future_client->firstrespond = $resp->text;
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
                        <strong class="text-primary">Assigned To: </strong><strong>' . $assignedUsers . '</strong></p><br>
                       </div><div class="row d-flex justify-content-between"><p>' . $resp->text . ' <br> <small><a href="/future-client-respond-delete/' . $resp->id . '" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>
                        </div>
                    </div>';
            }

            // Process files
            $fil = '';
            foreach ($future_client->files as $file) {
                $fil .= '<tr>
                            <td style="width: 80%;"><a href="' . $file->url . '" target="_blank" class="text-decoration-none">' . $file->url . '</a></td>
                            <td style="width: 20%;"><a href="/future-client-file-delete/' . $file->id . '" class="btn btn-sm btn-danger delete-file">Delete</a></td>
                        </tr>';
            }

            $future_client->comment = $dd;
            $future_client->respond = $res;
            $future_client->file = $fil;
            $future_client->allusers = $userss;

            return Datatables::of(collect([$future_client]))
                ->addColumn(
                    'action',
                    '<div class="btn-group">

            <button type="button" class="btn btn-primary btn-rounded dropdown-toggle btn-xs p-2" 
                data-toggle="dropdown" aria-expanded="false">Action
                <span class="caret"></span><span class="sr-only">
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-right p-3" role="menu">
              
            @can("future-client")
                <li class=""><a href="{{action(\'FutureClientController@edit\', [$id])}}" class="edit-future-client text-decoration-none"><i class="btn btn-sm btn-dark mdi mdi-table-edit p-1 m-1" title="Edit"></i> Edit</a> </li>
            @endcan
            @can("future-client")
                <li class=""><a href="{{action(\'FutureClientController@destroy\', [$id])}}" class="delete-future-client text-decoration-none"><i class="btn btn-sm btn-danger  mdi mdi-delete p-1 m-1" title="Delete"></i> Delete</a></li>
            @endcan     
            </ul></div>'
                )
                ->addColumn(
                    'comment',
                    '<div class="dropdown p-1">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $firstcomment }}
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuButton1" style="width:500px">
                        <li><form method="POST" action="/post-future-client-comments">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="future_client_id" id="future-client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-9">
                                    <input type="text" name="text" id="comment-text" placeholder="Enter Your Comment..." class="form-control" required>
                                    <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary me-2 float-end" id="submit-future-client-form">Comment</button>
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
                        <li><form method="POST" action="/post-future-client-responds">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="future_client_id" id="future-client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-3">
                                    <input type="date" name="date" class="form-control" required>
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
                                    <select name="assigned_user_id[]" class="form-control select2 future_client_response_users" multiple>
                                        {!! $allusers !!} 
                                    </select>  
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-sm btn-primary me-1 float-end" id="submit-future-client-form">Respond</button>
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
                        <li><form method="POST" action="/post-future-client-files" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="future_client_id" id="future-client-id" value="{{$id}}"> 
                        <div class="row">
                            <div class="form-group row mb-1">
                                <div class="col-sm-10 p-1">   
                                    <input type="file" name="files[]" placeholder="Select Files" class="form-control" multiple> 
                                </div>   
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary me-2 float-end" id="submit-future-client-form">Upload</button>
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
