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
use App\Models\Position;
use App\Models\Tender;
use App\Models\TenderComment;
use App\Models\TenderFile;
use App\Models\TenderRespond;
use App\Models\User;
use App\Notifications\TenderRespond as NotificationsTenderRespond;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class TenderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:tender', ['only' => [
            'index', 'show', 'create', 'store', 'edit', 'update', 'destroy',
            'files', 'post_tender_files', 'delete_files', 'comments', 'post_tender_comments', 'delete_comment',
            'responds', 'post_tender_responds', 'delete_respond'
        ]]);
    }

    public function index()
    {
        $user = auth()->user();
        $user->userdetail;

        $cities = City::orderBy('name', 'desc')->get();
        $groups = Group::orderBy('name', 'desc')->get();
        $clients = Client::orderBy('company_name', 'asc')->get();

        $users = User::all();

        $userss = "";
        foreach ($users  as $user) {
            $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }
 

        $allgroups = Group::with(['clients.tenders.city'])->get();

        $data = [];
        foreach ($allgroups as $group) {
            $groupData = [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'tenders' => []
            ];

            foreach ($group->clients as $client) {
                foreach ($client->tenders as $tender) {
                    $groupData['tenders'][] = [
                        "tender_id" => $tender->id,
                        "tender_city_name" => $tender->city->name,
                        "tender_city_code" => $tender->city->code,
                        "client_company_name" => $client->company_name,
                        "tender_tender_number" => $tender->tender_number,
                        "tender_status" => $tender->status,
                        "tender_assigned_number" => $tender->assigned_number,
                        "tender_year" => $tender->year,
                        "tender_description" => $tender->description,
                        "tender_start_date" => $tender->start_date,
                        "tender_close_date" => $tender->close_date,
                        "tender_announce_date" => $tender->announce_date,
                        "tender_submit_date" => $tender->submit_date,
                        "tender_period" => $tender->period,
                        "tender_term" => $tender->term,
                        "tender_amount" => $tender->amount,
                        "comments" => $tender->comments,
                        "responds" => $tender->responds,
                        "files" => $tender->files,
                    ];
                }
            }

            $data[] = $groupData;
        }
 

        return view('tenders.index')->with(compact('user', 'cities', 'clients', 'groups', 'data', 'userss'));
    }

    public function create()
    {
        $user = auth()->user();
        $user->userdetail;

        $cities = City::orderBy('name', 'desc')->get();
        $clients = Client::orderBy('company_name', 'asc')->get();
        $users = User::all();

        $userss = "";
        foreach ($users as $user) {
            $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }

        return view('tenders.create', compact('cities', 'clients', 'users', 'userss'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'client_id' => 'required',
            'city_id' => 'required',
            'tender_number' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        $input = $request->only([
            'client_id', 'tender_number', 'city_id', 'status', 'year', 'description', 'assigned_number',
            'start_date', 'close_date', 'announce_date', 'submit_date', 'period', 'term', 'amount', 'user_id'
        ]);

        $input['year'] = Carbon::createFromFormat('Y-m-d', $input['year'])->setTimeFrom(Carbon::now());
        $input['start_date'] = Carbon::createFromFormat('Y-m-d', $input['start_date'])->setTimeFrom(Carbon::now());
        $input['close_date'] = Carbon::createFromFormat('Y-m-d', $input['close_date'])->setTimeFrom(Carbon::now());
        $input['announce_date'] = Carbon::createFromFormat('Y-m-d', $input['announce_date'])->setTimeFrom(Carbon::now());
        $input['submit_date'] = Carbon::createFromFormat('Y-m-d', $input['submit_date'])->setTimeFrom(Carbon::now());


        // $user = auth()->user();
        // $input['user_id'] = $user->id;

        try {
            Tender::create($input);
            $output = array('success' => true, 'message' => "Tender created successfully");
        } catch (Exception $e) {
            $output = array('success' => false, 'message' => "Something went wrong!");
        }

        // Handle both AJAX and regular form submissions
        if (request()->ajax()) {
            return $output;
        } else {
            if ($output['success']) {
                return redirect("tenders-by-city")->with('success', $output['message']);
            } else {
                return redirect()->back()->with('error', $output['message'])->withInput();
            }
        }
    }

    public function show($id)
    {

        $user = auth()->user();
        $user->userdetail;
        $tender = Tender::where('id', $id)->with(['comments', 'responds', 'files', 'client', 'city', 'user'])->first(); 

        return view('tenders.show', compact('tender' ));
    }

    public function comments($id)
    {
        $user = auth()->user();
        $user->userdetail;
        $tender = Tender::findOrFail($id);
        $comments = $tender->comments;
        return view('tenders.comments_modal', compact('tender', 'comments'))->render();
    }

    public function post_tender_comments(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $tender_id = $request->get('tender_id');
                $text = $request->get('text');

                TenderComment::create([
                    "tender_id" => $tender_id,
                    "commented_by" => $user->id,
                    "text" => $text
                ]);
                $output = array('success' => true, 'message' => "Commented successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }
            return $output;
        } else {
            try {
                $user = auth()->user();
                $tender_id = $request->get('tender_id');
                $text = $request->get('text');

                TenderComment::create([
                    "tender_id" => $tender_id,
                    "commented_by" => $user->id,
                    "text" => $text
                ]);
                $output = array('success' => true, 'message' => "Commented successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }
            return redirect('/tenders')->with($output);
        }
    }

    public function delete_comment($id)
    {
        if (request()->ajax()) {
            try {
                $comment = TenderComment::find($id);
                if (!empty($comment)) {
                    $comment->delete();
                }
                $output = array('success' => true, 'message' => "Comment is deleted successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }
            return $output;
        } else {
            try {
                $comment = TenderComment::find($id);
                if (!empty($comment)) {
                    $comment->delete();
                }
                $output = array('success' => true, 'message' => "Comment is deleted successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }
            return redirect('/tenders')->with($output);
        }
    }

    public function responds($id)
    {
        $user = auth()->user();
        $user->userdetail;
        $tender = Tender::findOrFail($id);
        $responds = $tender->responds;
        $users = User::all();
        return view('tenders.responds_modal', compact('tender', 'responds', 'users'))->render();
    }

    public function post_tender_responds(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $tender_id = $request->get('tender_id');
                $text = $request->get('text');
                $subject = $request->get('subject');
                $date = $request->get('date');
                $time = $request->get('time');
                $assigned_user_id = $request->get('assigned_user_id');
                
                $formattedDateTime = Carbon::createFromFormat('Y-m-d', $date)
                    ->setTimeFrom(Carbon::createFromFormat('H:i', $time));

                $tender = TenderRespond::create([
                    "tender_id" => $tender_id,
                    "responded_by" => $user->id,
                    "subject" => $subject,
                    "assigned_user_id" => $assigned_user_id,
                    "date" => $formattedDateTime,
                    "time" => $time,
                    "text" => $text,
                ]);

                $dt = $tender->date->format('M, d Y H:i:s A');

                if ($assigned_user_id) {
                    foreach ($assigned_user_id as $uid) {
                        User::find($uid)->notify(new NotificationsTenderRespond($dt, $user->name, $tender->subject, $tender->text));
                    }
                }

                $output = array('success' => true, 'message' => "Responded successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => "Something went wrong!");
            }
            return $output;
        } else {
            try {
                $user = auth()->user();
                $tender_id = $request->get('tender_id');
                $text = $request->get('text');
                $subject = $request->get('subject');
                $date = $request->get('date');
                $assigned_user_id = $request->get('assigned_user_id');
                $formattedDateTime = Carbon::createFromFormat('Y-m-d', $date)
                    ->setTimeFrom(Carbon::now());

                $tender = TenderRespond::create([
                    "tender_id" => $tender_id,
                    "responded_by" => $user->id,
                    "subject" => $subject,
                    "assigned_user_id" => $assigned_user_id,
                    "date" => $formattedDateTime,
                    "text" => $text,
                ]);

                $dt = $tender->date->format('M, d Y H:i:s A');

                User::find($tender->assigned_user_id)->notify(new NotificationsTenderRespond($dt, $user->name, $tender->subject, $tender->text));

                $output = array('success' => true, 'message' => "Responded successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => "Something went wrong!");
            }
            return redirect('/tenders')->with($output);
        }
    }

    public function delete_respond($id)
    {
        if (request()->ajax()) {
            try {
                $respond = TenderRespond::find($id);
                if (!empty($respond)) {
                    $respond->delete();
                }
                $output = array('success' => true, 'message' => "Respond is deleted successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = array('success' => false, 'message' => "Something went wrong!");
            }
            return $output;
        } else {
            try {
                $respond = TenderRespond::find($id);
                if (!empty($respond)) {
                    $respond->delete();
                }
                $output = array('success' => true, 'message' => "Respond is deleted successfully");
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = array('success' => false, 'message' => "Something went wrong!");
            }
            return redirect('/tenders')->with($output);
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
        $tender = Tender::findOrFail($id);
        $files = $tender->files;
        return view('tenders.files_modal', compact('tender', 'files'))->render();
    }

    public function post_tender_files(Request $request)
    {
        if (request()->ajax()) {
            try {
                $user = auth()->user();
                $tender_id = $request->get('tender_id');
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $originName = $file->getClientOriginalName();
                        $fileName = pathinfo($originName, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $fileName . '_' . time() . '.' . $extension;
                        $file->move(public_path('tender-files/'), $fileName);
                        $url = 'tender-files/' . $fileName;

                        TenderFile::create([
                            "tender_id" => $tender_id,
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
        } else {
            try {
                $user = auth()->user();
                $tender_id = $request->get('tender_id');
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $originName = $file->getClientOriginalName();
                        $fileName = pathinfo($originName, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $fileName . '_' . time() . '.' . $extension;
                        $file->move(public_path('tender-files/'), $fileName);
                        $url = 'tender-files/' . $fileName;

                        TenderFile::create([
                            "tender_id" => $tender_id,
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
            return redirect('/tenders')->with($output);
        }
    }

    public function delete_file($id)
    {
        if (request()->ajax()) {
            try {
                $file = TenderFile::find($id);

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
        } else {
            try {
                $file = TenderFile::find($id);

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
            return redirect('/tenders')->with($output);
        }
    }


    public function edit($id)
    {

        $user = auth()->user();
        $user->userdetail;

        $tender = Tender::find($id);
        $cities = City::orderBy('name', 'desc')->get();
        $clients = Client::orderBy('company_name', 'asc')->get();

        $tender['start_date'] = Carbon::parse($tender['start_date'])->format('Y-m-d');
        $tender['close_date'] = Carbon::parse($tender['close_date'])->format('Y-m-d');
        $tender['announce_date'] = Carbon::parse($tender['announce_date'])->format('Y-m-d');
        $tender['submit_date'] = Carbon::parse($tender['submit_date'])->format('Y-m-d');
        $tender['year'] = Carbon::parse($tender['year'])->format('Y-m-d');

        return view('tenders.edit_modal', compact('tender', 'cities', 'clients'))->render();
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required',
                'city_id' => 'required',
                'tender_number' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);

        $input = $request->only([
            'client_id', 'tender_number', 'city_id', 'status', 'year', 'description', 'assigned_number',
            'start_date', 'close_date', 'announce_date', 'submit_date', 'period', 'term', 'amount'
        ]);

        $input['year'] = Carbon::createFromFormat('Y-m-d', $input['year'])->setTimeFrom(Carbon::now());
        $input['start_date'] = Carbon::createFromFormat('Y-m-d', $input['start_date'])->setTimeFrom(Carbon::now());
        $input['close_date'] = Carbon::createFromFormat('Y-m-d', $input['close_date'])->setTimeFrom(Carbon::now());
        $input['announce_date'] = Carbon::createFromFormat('Y-m-d', $input['announce_date'])->setTimeFrom(Carbon::now());
        $input['submit_date'] = Carbon::createFromFormat('Y-m-d', $input['submit_date'])->setTimeFrom(Carbon::now());

        $tender = Tender::find($id);

        $tender->client_id =  $request->input('client_id');
        $tender->city_id =  $request->input('city_id');
        $tender->tender_number =  $request->input('tender_number');
        $tender->status =  $request->input('status');
        $tender->description =  $request->input('description');
        $tender->assigned_number =  $request->input('assigned_number');
        $tender->period =  $request->input('period');
        $tender->term =  $request->input('term');
        $tender->amount =  $request->input('amount');
        $tender->year =  Carbon::createFromFormat('Y-m-d', $request->input('year'))->setTimeFrom(Carbon::now());
        $tender->start_date =  Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->setTimeFrom(Carbon::now());
        $tender->close_date =  Carbon::createFromFormat('Y-m-d', $request->input('close_date'))->setTimeFrom(Carbon::now());
        $tender->announce_date =  Carbon::createFromFormat('Y-m-d', $request->input('announce_date'))->setTimeFrom(Carbon::now());
        $tender->submit_date =  Carbon::createFromFormat('Y-m-d', $request->input('submit_date'))->setTimeFrom(Carbon::now());
        $tender->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tender updated successfully'
            ]);
        }

        return redirect("tenders")->with('success', 'Tender  updated successfully');
        
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while updating tender'
                ]);
            }
            return redirect()->back()->with('error', 'Something went wrong while updating tender');
        }
    }

    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $tender = Tender::find($id);
                if (!empty($tender)) {
                    $tender->comments()->delete();
                    $tender->responds()->delete();
                    $tender->files()->delete();
                    $tender->delete();
                }

                $output = array('success' => true, 'message' => "Tender  is deleted successfully");
            } catch (\Exception $e) {

                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
            }

            return $output;
        }
    }


    public function showCalendar()
    {
        return view('tenders.calendar');
    }

    public function showCalendarTenders()
    {
        return view('tenders.calendar_tenders');
    }

    public function showCalendarFutureClients()
    {
        return view('tenders.calendar_future_clients');
    }

    public function showCalendarClients()
    {
        return view('tenders.calendar_clients');
    }

    public function getClosingDates(Request $request) {
        $year = $request->year;
        $month = $request->month;
    
        $tenders = DB::table('tenders')
            ->whereYear('close_date', $year)
            ->whereMonth('close_date', $month)
            ->select('close_date', 'description', 'announce_date')
            ->get()
            ->map(function ($t) {
                return [
                    'close_date' => \Carbon\Carbon::parse($t->close_date)->toDateString(), // Ensure correct format
                    'description' => $t->description,
                    'announce_date' => \Carbon\Carbon::parse($t->announce_date)->toDateString()
                ];
            });

        $future_clients = DB::table('future_clients')
        ->whereYear('start_date', $year)
        ->whereMonth('start_date', $month)
        ->select('start_date', 'description')
        ->get()
        ->map(function ($fc) {
            return [
                'start_date' => \Carbon\Carbon::parse($fc->start_date)->toDateString(),
                'description' => $fc->description
            ];
        });

        $client_responds = DB::table('client_responds')
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->select('date', 'text', 'subject')
        ->get()
        ->map(function ($cr) {
            return [
                'date' => \Carbon\Carbon::parse($cr->date)->toDateString(),
                'text' => $cr->text,
                'subject' => $cr->subject
            ];
        });
        $future_client_responds = DB::table('future_client_responds')
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->select('date', 'text', 'subject')
        ->get()
        ->map(function ($fcr) {
            return [
                'date' => \Carbon\Carbon::parse($fcr->date)->toDateString(),
                'text' => $fcr->text,
                'subject' => $fcr->subject
            ];
        });
        $tender_responds = DB::table('tender_responds')
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->select('date', 'text', 'subject')
        ->get()
        ->map(function ($tr) {
            return [
                'date' => \Carbon\Carbon::parse($tr->date)->toDateString(),
                'text' => $tr->text,
                'subject' => $tr->subject
            ];
        });
    
            $data  = [
                "tenders" => $tenders,
                "future_clients" => $future_clients,
                "client_responds" => $client_responds,
                "future_client_responds" => $future_client_responds,
                "tender_responds" => $tender_responds,
            ];
        return response()->json($data);
    }

    public function tendersByCity()
    {
        if (request()->ajax()) {
            $cities = City::with(['tenders.client'])->get()
                ->groupBy('code')
                ->map(function($cities) {
                    return [
                        'code' => $cities->first()->code,
                        'cities' => $cities->map(function($city) {
                            return [
                                'id' => $city->id,
                                'name' => $city->name,
                                'clients' => $city->tenders->map(function($tender) {
                                    return [
                                        'id' => $tender->id,
                                        'name' => $tender->client->company_name . ' - ' . $tender->tender_number
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
        $users = User::all();
        $userss = "";
        foreach ($users as $user) {
            $userss .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }

        return view('tenders.by_city', compact('clients', 'userss'));
    }

    public function getTenderDetails($id)
    {
        if (request()->ajax()) {
            $tender = Tender::with(['client', 'comments.comment_by', 'responds.responds_by', 'files'])->find($id);
            
            if (!$tender) {
                return response()->json(['data' => []]);
            }

            // Generate comments HTML
            $commentsHtml = '<div class="dropdown p-1">
                <button class="btn btn-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    ' . ($tender->comments->count() > 0 ? $tender->comments->first()->text : 'No Comments yet') . '
                </button>
                <ul class="dropdown-menu p-1 bg-light" style="width:750px">
                    <li>
                        <form method="POST" action="/post-tender-comments">
                            <input type="hidden" name="_token" value="' . csrf_token() . '" />
                            <input type="hidden" name="tender_id" value="' . $tender->id . '">
                            <div class="row">
                                <div class="form-group row mb-1">
                                    <div class="col-sm-9">
                                        <input type="text" name="text" placeholder="Enter Your Comment..." class="form-control" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary me-2 float-end">Comment</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>';

            foreach ($tender->comments as $comment) {
                $commentsHtml .= '<div class="m-1 p-1 border rounded">
                    <div class="row">
                        <p><strong class="text-success">' . $comment->comment_by->name . '</strong> - <small>' . $comment->created_at . '</small></p>
                    </div>
                    <div class="row">
                        <p>' . $comment->text . ' <br> <small><a href="/tender-comment-delete/' . $comment->id . '" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>
                    </div>
                </div>';
            }
            $commentsHtml .= '</ul></div>';

            // Generate responses HTML
            $responsesHtml = '<div class="dropdown p-1">
                <button class="btn btn-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    ' . ($tender->responds->count() > 0 ? $tender->responds->first()->text : 'No Responds yet') . '
                </button>
                <ul class="dropdown-menu p-1 bg-light" style="width:650px">
                    <li>
                        <form method="POST" action="/post-tender-responds">
                            <input type="hidden" name="_token" value="' . csrf_token() . '" />
                            <input type="hidden" name="tender_id" value="' . $tender->id . '">
                            <div class="row">
                                <div class="form-group row mb-1">
                                    <div class="col-sm-2">
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="time" name="time" class="form-control" required>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="subject" class="form-control" required>
                                            <option value="">Select Subject</option>
                                            <option value="Subject One">Subject One</option>
                                            <option value="Subject Two">Subject Two</option>
                                            <option value="Subject Three">Subject Three</option>
                                            <option value="Subject Four">Subject Four</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name="text" placeholder="Enter Your respond..." class="form-control" required>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="assigned_user_id[]" class="form-control tender_response_users" multiple>';

            $users = User::all();
            foreach ($users as $user) {
                $responsesHtml .= '<option value="' . $user->id . '">' . $user->name . '</option>';
            }

            $responsesHtml .= '</select>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit" class="btn btn-primary me-2 float-end">Respond</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>';

            foreach ($tender->responds as $respond) {
                $assignedUsers = "";
                if ($respond->assigned_user_id) {
                    $assignedUsers = collect($respond->assigned_user_id)->map(function($uid) {
                        $u = User::find($uid);
                        return $u ? $u->name : '';
                    })->filter()->implode(', ');
                }

                $responsesHtml .= '<div class="m-1 p-1 border rounded">
                    <div class="row">
                        <p><strong class="text-primary">' . $respond->subject . '</strong> - 
                           <strong class="text-success">' . $respond->responds_by->name . '</strong> - 
                           <small>' . $respond->date . '</small> - 
                           <small>' . $respond->time . '</small> - 
                           <strong class="text-primary">Assigned To: </strong><strong>' . $assignedUsers . '</strong>
                        </p>
                    </div>
                    <div class="row">
                        <p>' . $respond->text . ' <br> <small><a href="/tender-respond-delete/' . $respond->id . '" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>
                    </div>
                </div>';
            }
            $responsesHtml .= '</ul></div>';

            // Generate files HTML
            $filesHtml = '<div class="dropdown p-1">
                <button class="btn btn-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Files
                </button>
                <ul class="dropdown-menu p-1 bg-light" style="width:500px">
                    <li>
                        <form method="POST" action="/post-tender-files" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="' . csrf_token() . '" />
                            <input type="hidden" name="tender_id" value="' . $tender->id . '">
                            <div class="row">
                                <div class="form-group row mb-1">
                                    <div class="col-sm-10 p-1">
                                        <input type="file" name="files[]" class="form-control" multiple>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary me-2 float-end">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-md-12 p-1 border rounded">
                                <table class="w-100">';

            foreach ($tender->files as $file) {
                $filesHtml .= '<tr>
                    <td style="width: 80%;"><a href="' . $file->url . '" target="_blank" class="text-decoration-none">' . $file->url . '</a></td>
                    <td style="width: 20%;"><a href="/tender-file-delete/' . $file->id . '" class="btn btn-sm btn-danger delete-file">Delete</a></td>
                </tr>';
            }

            $filesHtml .= '</table>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>';

            // Generate action buttons
            $actionHtml = '';
            if (auth()->user()->can('tender')) {
                $actionHtml = '<a href="/tenders/' . $tender->id . '/edit" class="edit-tender btn btn-dark mdi mdi-table-edit p-1 m-1"></a>
                               <a href="/tenders/' . $tender->id . '" class="delete-tender btn btn-danger mdi mdi-delete p-1 m-1"></a>';
            }

            $data = [
                [
                    'tender_id' => $tender->id,
                    'client_company_name' => $tender->client->company_name,
                    'comment' => $commentsHtml,
                    'respond' => $responsesHtml,
                    'files' => $filesHtml,
                    'action' => $actionHtml
                ]
            ];

            return response()->json(['data' => $data]);
        }
    }
    
    
}
