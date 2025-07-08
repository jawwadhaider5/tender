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

        return $output;
        // return redirect("tenders")->with('success', 'Tenders created successfully');
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
        // if (request()->ajax()) {
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
        // return $output;
        // }
    }

    public function delete_comment($id)
    {
        // if (request()->ajax()) {
        try {
            $comment = TenderComment::find($id);
            if (!empty($comment)) {
                $comment->delete();
            }
            $output = array('success' => true, 'message' => "Comment  is deleted successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = array('success' => false, 'message' => __("messages.something_went_wrong"));
        }
        return redirect('/tenders')->with($output);
        // return $output;
        // }
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
        // if (request()->ajax()) {
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
        // return $output;
        // }
    }

    public function delete_respond($id)
    {

        // if (request()->ajax()) {
        try {
            $respond = TenderRespond::find($id);
            if (!empty($respond)) {
                $respond->delete();
            }
            $output = array('success' => true, 'message' => "Respond  is deleted successfully");
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = array('success' => false, 'message' => "Something went wrong!");
        }
        return redirect('/tenders')->with($output);
        // return $output;
        // }
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
        // if (request()->ajax()) {
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
        // return $output;
        // }
    }

    public function delete_file($id)
    {
        // if (request()->ajax()) {
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

        //     return $output;
        // }
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

        return redirect("tenders")->with('success', 'Tender  updated successfully');
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
    
    
}
