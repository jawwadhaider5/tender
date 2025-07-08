<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:dashbaord', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]); 
    }


    public function index()
    {
 
        $user = auth()->user();
        $user->userdetail; 
        
  

        if (request()->ajax()) {


            $todate = request()->get('to');
            $fromdate = request()->get('from');

            

            if(!empty($fromdate)  && !empty($todate)){

                // in below query 'limit 1' means give one row of data

             $totalContainer = DB::select("SELECT COUNT(*) as tc FROM containers WHERE `creation_date` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");
             $unsolditem = DB::select("SELECT COUNT(*) as uni FROM sells  WHERE sells.status = 'unsold'
                           AND  `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");

             $supplier = DB::select("SELECT COUNT(*) as sup FROM user_details  WHERE user_details.account_type = 'Supplier'
                          AND  `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");

             $user = DB::select("SELECT COUNT(*) as us FROM users WHERE `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");              
             $totalCustomer = DB::select("SELECT COUNT(*) as custom FROM customers WHERE `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");              
             $totalPayment = DB::select("SELECT COUNT(*) as tpay FROM payments WHERE `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");              
             $totalBill = DB::select("SELECT COUNT(*) as tbil FROM bills WHERE `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");              
             $totalPaymentAmount = DB::select("SELECT COALESCE(SUM(payment_amount), 0) as tpam FROM transections WHERE `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");
             
            $paidTransection = DB::select("SELECT COUNT(*) as pat FROM transections WHERE transections.payment_status = 'paid' AND
            `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");
             $totalReceivedAmount = DB::select("SELECT COALESCE(SUM(received_amount), 0) as tram FROM payments WHERE `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1");
                 
            $dueTransection = DB::select("SELECT COUNT(*) as dut FROM transections WHERE transections.payment_status = 'due' OR transections.payment_status = 'partial' AND
            `created_at` BETWEEN '".$fromdate."' AND '".$todate."' limit 1"); 
            }
 

             $dailyBill = DB::select("SELECT COUNT(*) AS billcount , created_at As label  FROM bills WHERE 
             created_at BETWEEN '$fromdate' AND '$todate' GROUP BY bills.id");

            $data = [
               'dailyBill' => $dailyBill,
               'totalContainer' => $totalContainer,
               'totalCustomer' => $totalCustomer,
               'totalPayment' => $totalPayment,
               'totalBill' => $totalBill,
               'paidTransection' => $paidTransection,
               'dueTransection' => $dueTransection,
               'unsolditem' => $unsolditem,
               'supplier' => $supplier,
               'user' => $user,
               'totalPaymentAmount' => $totalPaymentAmount,
               'totalReceivedAmount' => $totalReceivedAmount,              
            ];
           
            return json_encode($data);
            // return json_encode("ajaz ok");
            
        }

        return view('home')->with(compact('user'));
    }
 
    public function create()
    { 
    }
 
    public function store(Request $request)
    { 
    }
 
    public function show($id)
    {
        //
    } 
    public function edit($id)
    {
        //
    } 
    public function update(Request $request, $id)
    {
        //
    }
 
    public function destroy($id)
    {
        //
    }
}
