<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FutureClient;
use App\Models\Subscribe;
use App\Models\Tender;
use App\Models\TenderComment;
use App\Models\TenderFile;
use App\Models\TenderRespond;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{ 
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    public function index()
    {

        $user = auth()->user();
        $userdetai = $user->userdetail;  

        return view('home')->with(compact('user','userdetai'));
    } 

    public function getDashboardData()
    {
        $approvedTenders = Tender::where('status', 'approved')->count();
        $not_approvedTenders = Tender::where('status', 'not approved')->count();
        $pendingTenders = Tender::where('status', 'pending')->count();
        $totalClients = Client::count();
        $totalFutureClients = FutureClient::count();

        $recentActivities = [
            "New client registered: <b class='text-primary'>".(Client::latest()->first()->company_name ?? "N/A")."</b>",
            "New Future client registered: <b class='text-primary'>".(FutureClient::latest()->first()->description ?? "N/A")."</b>",
            "New tender <b class='text-primary'>".(Tender::latest()->first()->description ?? 0)."</b> added", 
            "Latest tender comment <b class='text-primary'>".(TenderComment::latest()->first()->text ?? 'N/A')."</b>",
            "Latest tender Respond <b class='text-primary'>".(TenderRespond::latest()->first()->text ?? 'N/A')."</b>",
            "Latest tender file uploaded <b class='text-primary'><a target='_blank' href='".(TenderFile::latest()->first()->url ?? '#')."'>".(TenderFile::latest()->first()->url ?? 'N/A')."</a></b>", 
        ];

        $futureClients = FutureClient::select('id', 'description', 'coming_date')->orderBy('coming_date')->limit(5)->get();
        $upcomingTenders = Tender::select('id', 'description', 'close_date')->where('status', 'approved')->orderBy('close_date')->limit(5)->get();

        return response()->json([
            'approved_tenders' => $approvedTenders,
            'not_approved_tenders' => $not_approvedTenders,
            'pending_tenders' => $pendingTenders,
            'total_clients' => $totalClients,
            'total_future_clients' => $totalFutureClients,
            'recent_activities' => $recentActivities,
            'future_clients' => $futureClients,
            'upcoming_tenders' => $upcomingTenders
        ]);
    }



    
    
  


   


}
