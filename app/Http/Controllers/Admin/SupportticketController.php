<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\BaseController;
use App\Mail\Ticketmail;
use App\Models\Customer;
use App\Models\CustomerEnquire;
use App\Models\Notification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class SupportticketController extends Controller
{
   public $page = 'ticket';
    public function ticketlist(Request $request)
    {
        if ($request->ajax()) {
            $data = CustomerEnquire::with('customer')->get();
            return datatables::of($data)
              ->addIndexColumn()
              ->addColumn('status', function ($row) {
                $checked = $row->status == 'open' ? 'checked' : '';
                return '<div class="form-check form-switch form-switch-md mb-2">
                        <input class="form-check-input" type="checkbox" id="toggleSwitch_' . $row->id . '" ' . $checked . ' onclick="changeStatus(\'' . route('support.tickit.status', ['id' => $row->id]) . '\', \'status' . $row->id . '\')">
                        <label class="form-check-label" for="toggleSwitch_' . $row->id . '"></label>
                    </div>';
              })
              ->addColumn('action', function ($row) {
                $actionBtn = view('admin.supportticketmanagement.button', ['item' => $row, 'page' => $this->page]);
                return $actionBtn;
          })
          ->rawColumns(['status','action'])
          ->make(true);
          }
            return view('admin.supportticketmanagement.ticketlist');  
    }
    // public function ticket()
    // {
    //      $tickitno = rand(100000000, 999999999);
    //     return view('admin.supportticketmanagement.ticketadd',compact('tickitno'));
    // }
    public function add()
    {   
        $customers = Customer::all();
        $tickitno = rand(100000000, 999999999);
        return view('admin.supportticketmanagement.ticketadd',compact('tickitno','customers'));
    }
    public function ticketadd(Request $request)
    {  
            $validatedata = Validator::make($request->all(), [
                'customer_id' => 'required',
                'ticket_no' => 'required'
            ]);
    
            if ($validatedata->fails()) {
                return redirect()->back()->withErrors($validatedata)->withInput();
            } else {
                $ticketadd = new CustomerEnquire();
                $ticketadd->customer_id = $request->customer_id;
                $ticketadd->subject = $request->subject;
                $ticketadd->type = $request->type;
                $ticketadd->ticket_no = $request->ticket_no;
                $ticketadd->save();
            }
            if ($ticketadd->save()){
                $notification = new Notification();
                $notification->customer_id =  $ticketadd->customer_id;
                $notification->message = 'Ticket Added Successsfully';
                $notification->date_time = Carbon::now();
                $notification->is_read = 'unread';
                $notification->notification_type = 'system_gen';
                $notification->user_id = auth()->user()->id;
                $notification->long_content = "Thank you for reaching out to PrestoRewards support. Your request has been received,";
                $notification->save();
            }

            $customer = Customer::where('id',$ticketadd->customer_id)->first();
            if($customer->email_id !== ''){
             $this->ticketmail($customer->email_id,$ticketadd->id);
            }
            return view('admin.supportticketmanagement.ticketlist');
    }

    public function tickit_status($id)
    {
        $customer_sts = CustomerEnquire::where('id', $id)->first();

    if ($customer_sts->status == "close") {
      $customer_sts->status = "open";
      $customer_sts->save();
      if ($customer_sts->save()); {
        return redirect()->route('admin.supportticketmanagement.ticketlist');
      }
    } else {

      $customer_sts->status = "close";
      $customer_sts->save();
      if ($customer_sts->save()); {
        return redirect()->route('admin.supportticketmanagement.ticketlist');
      }
    }
    }

    public function ticket_view($id)
    {
       
        $view_ticet = CustomerEnquire::with('customer','convertation')->where('id', $id)->first();
        $data = Conversation::where('ticket_no',$view_ticet->ticket_no)->where('reply','customer')->update([
            'status' => 'read',
        ]);
        return view('admin.supportticketmanagement.ticketview', compact('view_ticet'));
    }

    public function comments(Request $request)
    {
        // $customers = CustomerEnquire::with('customer')->get();
        // return view('admin.supportticketmanagement.comments', compact('customers'));
        if($request -> ajax())
        {
            $data = CustomerEnquire::with('customer')->get();
            return datatables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if($row->status == 'open')
                {
                    $status = "<span class='badge bg-warning'>OPEN</span>";
                }else{
                    $status = "<span class='badge bg-success'>CLOSE</span>";
                }

                return $status;
            })
            ->addColumn('action', function ($row) {
                $actionBtn = view('admin.supportticketmanagement.button', ['item' => $row, 'page' => $this->page]);
                return $actionBtn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        }

        return view('admin.supportticketmanagement.comments');
    }

    public function view($id)
    {
        $data = CustomerEnquire::with('Customer')->where('id',$id)->first();

        $conversation = Conversation::where('ticket_no',$data->ticket_no)->get();
        
        $status = Conversation::where('ticket_no',$data->ticket_no)->where('reply','customer')->update([
            'status' => 'read',
        ]);

        return view('admin.supportticketmanagement.chat',compact('data','conversation'));
    }

    public function messege(Request $request)
    {
        $validatedata = Validator::make($request->all(), [
            'message' => 'required',
            'ticket_no' => 'required|exists:conversations,ticket_no'
        ]);

        if ($validatedata->fails()) {
            return redirect()->back()->withErrors($validatedata)->withInput();
        } else {

            $id = auth()->user()->id;

            $entity = new Conversation();
            $entity->ticket_no = $request->ticket_no;
            $entity->message = $request->message;
            $entity->status = "reply";
            $entity->reply_by = $id;
            $entity->reply = "admin";
            $entity->save();
            if ($entity->save()) {
                $customerEnquire = CustomerEnquire::select('customer_id')->where('ticket_no', $request->ticket_no)->first();
                $customer_id = $customerEnquire->customer_id; // Extract customer_id from the object
                $data = new Notification();
                $data->customer_id = $customer_id;
                $data->message = "you got a reply";
                $data->is_read = "unread";
                $data->date_time = Carbon::now();
                $data->notification_type = "admin_gen";
                $data->user_id = $id;
                $data->created_by = $id;
                $data->save();
            }
            return redirect()->back();
        }
    }

    public function enquiryread($id)
    {
        $enquiryread = CustomerEnquire::with('customer')->where('id', $id)->first();

        if ($enquiryread->status == 'unread') {
            $enquiryread->status = 'read';
            $enquiryread->save();
        }
        return view('admin.customermanagement.Enquirydetails', compact('enquiryread'));
    }
    public function replymessage(Request $request, $id)
    {
        $customer_reply = CustomerEnquire::where('id', $id)->first();

        $ticketNumber = Uuid::uuid4()->toString();

        $customer_reply->reply = $request->reply;
        $customer_reply->reply_date = now();
        $customer_reply->ticket_number = $ticketNumber;
        $customer_reply->update();

        return redirect()->route('admin.enquiry');
    }
    public function ticketmail($email_id,$id)
    {
       $data = CustomerEnquire::with('customer')->where('id',$id)->first();
        try{
            $ref_code = (string) rand(100000000, 999999999);
            $maildata = [
                'title' => 'Ticket Added Successfully',
                'ticket_no' => $data->ticket_no,
                'customer_id' => $data->customer->first_name . ' ' . $data->customer->last_name,
            ];
            Mail::to($email_id)->send(new Ticketmail($maildata,$ref_code));
        } catch (Throwable $t) {
            Log::error('Mail sending failed: ' . $t->getMessage());
            
        }
    }
}
