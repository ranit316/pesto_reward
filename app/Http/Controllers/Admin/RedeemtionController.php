<?php

namespace App\Http\Controllers\Admin;

use App\Events\PushNotification;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Wallet;
use App\Models\RedemptionRequest;
use App\Models\WalletTransaction;
use App\Models\Customer;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use DateTime;
use Illuminate\Http\Request;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\BaseController;
use App\Mail\RedeemptionStatus;
use App\Mail\redeemptionReject;
use Throwable;

class RedeemtionController extends Controller
{
    public function index(Request $request)
    {

        // $datas = RedemptionRequest::with('customer','coupon.couponRequest.company')->where('status','pending')->latest('created_at')->get();
        // return view('admin.redeemption.index',compact('datas'));


        if ($request->ajax()) {
            $data =  RedemptionRequest::with('customer', 'coupon.couponRequest.company', 'coupon')->where('status', 'pending')->latest('created_at')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    return '<input type="checkbox" value="' . $row->id . '" name="redeemption" />';
                })
                ->addColumn('action', function ($row) {
                    //$downloadUrl = route('request.rejected', ['id' => $row->id]);
                    //$viewUrl = route('request.approved', ['id' => $row->id]);
                    //$buttons = [
                    //'<a href="' . $viewUrl . '" class="edit btn btn-primary btn-sm">Approve</a>',
                    //'<a href="' . $downloadUrl . '" class="btn btn-primary btn-sm">Reject</a>',
                    // '<input type="checkbox" value="' . $row->id . '" name="redeemption" />',
                    // '<a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reject">Reject</a>',

                    // ];
                    // return implode(' ', $buttons);
                    $actionBtn = view('admin.redeemption.button', ['item' => $row]);
                    return $actionBtn;
                })
                ->rawColumns(['check', 'action'])
                ->make(true);
        }
        return view('admin.redeemption.index');
    }

    public function approve($id)
    {
        $user_id = auth()->user()->id;
        $redemption = RedemptionRequest::with('coupon')->where('id', $id)->first();

        $balanceData = Wallet::where('customer_id', $redemption->customer_id)
            ->select('balance', 'lifetime_credit')
            ->first();

        if ($balanceData) {
            $wallet = Wallet::where('customer_id', $redemption->customer_id)->first();
            $originalBalance = $balanceData->balance;
            $originalLifetimeCredit = $balanceData->lifetime_credit;

            $wallet->balance = $originalBalance + $redemption->amount;
            $wallet->lifetime_credit = $originalLifetimeCredit + $redemption->amount;
            $wallet->save();
            if ($wallet->save()) {
                $transsaction = new WalletTransaction();
                $transsaction->wallet_id = $wallet->id;
                $transsaction->transactiontype = 'cr';
                $transsaction->amount = $redemption->amount;
                $transsaction->description = "this is coupon redemption";
                $transsaction->status = "CREDITED";
                $transsaction->date = Carbon::now()->format('Y-m-d');
                $transsaction->created_by = $user_id;
                $transsaction->save();
            }

            if ($transsaction->save()) {
                $redemption->status = 'approved';
                $redemption->save();

                if ($redemption->save()) {

                    $timestamp = Carbon::now();

                    $entity = new Notification();
                    $ref_code = (string) rand(100000000, 999999999);
                    $entity->message = "Congratulations! Your redemption request for Presto Plast India Rewards App has been approvedRef No: $ref_code .Thank You!";
                    $entity->customer_id = $redemption->customer_id;
                    $entity->date_time = $timestamp;
                    $entity->notification_type = "system_gen";
                    $entity->is_read = "unread";
                    $entity->long_content = "We are excited to inform you that your redemption request has been rejected. Check your PrestoRewards app for more details";
                    $entity->save();
                }


                $email_id = Customer::where('id', $redemption->customer_id)->first();
                if ($email_id->email_id) {
                    // $subject = "Redemption Request Approved - Presto Plast India Rewards App !";
                    // $message = "approved";
                    $this->redemptionstsmail($email_id, $redemption);
                }
                $push_notification = "PrestoRewards: Redemption Approved!";
                $cus_id = $redemption->customer_id;
                // $reg_ids = AppSetting::where('customer_id', $cus_id)->latest()->first();
                // $reg_id = $reg_ids->mobile_id;
                // return $reg_id;
                event(new PushNotification($push_notification, $cus_id));
                //return $responsedata;
                return redirect()->route('redeemption');
            }
        }
    }

    public function reject(Request $request, $id)
    {
        //$id = $request->input('itemId');
        $redemption = RedemptionRequest::with('coupon')->where('id', $id)->first();
        if ($redemption) {
            $redemption->update([
                'status' => "rejected",
                'admin_comment' => $request->rejectionReason
            ]);
            $customer = Customer::where('id', $redemption->customer_id)->first();

            $timestamp = Carbon::now();
            $ref_code = (string) rand(100000000, 999999999);
            $entity = new Notification();
            $entity->message = "We regret to inform you that your redemption request for Presto Plast India Rewards App has been rejected. Ref No: $ref_code . Please visit our support page for assistance.";
            $entity->customer_id = $redemption->customer_id;
            $entity->date_time = $timestamp;
            $entity->notification_type = "system_gen";
            $entity->is_read = "unread";
            $entity->long_content = "We are excited to inform you that your redemption request has been rejected. Check your PrestoRewards app for more details";
            $entity->save();

            // if ($entity->save()) {
            //     $transsaction = new WalletTransaction();
            //     $transsaction->wallet_id = $wallet->id;

            //     $transsaction = $transsaction->wallet_id;
            //     $transsaction->save();
            // }
            if ($customer->email_id) {
                // $subject = "Redemption Request Rejected - Presto Plast India Rewards App !";
                // $message = "rejected";
                $this->redemptionrejectmail($customer, $redemption);
            }
        }
        //return redirect()->route('redeemption');
        $data = ['message' => 'coupon rejected'];
        return response($data);
    }

    public function ajaxAllApproved(Request $request)
    {
        $user_id = auth()->user()->id;
        $id_arr = json_decode($request->redeemptionReq, true);
        for ($i = 0; $i < count($id_arr); $i++) {

            $redemption = RedemptionRequest::where('id', $id_arr[$i])->first();

            $balanceData = Wallet::where('customer_id', $redemption->customer_id)
                ->select('balance', 'lifetime_credit')
                ->first();

            if ($balanceData) {
                $wallet = Wallet::where('customer_id', $redemption->customer_id)->first();
                $originalBalance = $balanceData->balance;
                $originalLifetimeCredit = $balanceData->lifetime_credit;

                $wallet->balance = $originalBalance + $redemption->amount;
                $wallet->lifetime_credit = $originalLifetimeCredit + $redemption->amount;
                $wallet->save();
                if ($wallet->save()) {
                    $transsaction = new WalletTransaction();
                    $transsaction->wallet_id = $wallet->id;
                    $transsaction->transactiontype = 'cr';
                    $transsaction->amount = $redemption->amount;
                    $transsaction->status = "CREDITED";
                    $transsaction->date = Carbon::now()->format('Y-m-d');
                    $transsaction->created_by = $user_id;
                    $transsaction->save();
                }

                if ($transsaction->save()) {
                    $redemption->status = 'approved';
                    $redemption->save();

                    if ($redemption->save()) {

                        $timestamp = Carbon::now();

                        $entity = new Notification();
                        $entity->message = "Your coupan redeemed";
                        $entity->customer_id = $redemption->customer_id;
                        $entity->date_time = $timestamp;
                        $entity->notification_type = "system_gen";
                        $entity->long_content = "We are excited to inform you that your redemption request has been approved. Check your PrestoRewards app for more details";
                        $entity->save();
                    }
                }
            }
        }  //end of foor loop

        $return_data = array("message" => "Approved Successfully", "status" => "success");
        return json_encode($return_data);
    }


    public function pending()
    {
        return view('admin.redeemption.pending');
    }
    public function rejected()
    {
        return view('admin.redeemption.rejected');
    }

    public function redemptionstsmail($email, $data)
    {
        // try{
        $ref_code = (string) rand(100000000, 999999999);
        $currentDate = Carbon::now()->format('Y-m-d');
        $maildata = [
            'title' => 'Redeemtion Update',
            'amount' => $data->amount,
            'coupon_id' => $data->coupon->coupon_code,
            'status' => $data->status,
            'first_name' => $email->first_name,
            'last_name' => $email->last_name,
            'referance_code' => $ref_code,
            'currentDate' => $currentDate,
        ];
        Mail::to($email->email_id)->send(new RedeemptionStatus($maildata, $ref_code));
        // }catch(Throwable $t)
        // {
        //     Log::error('mail sending fail: ' . $t->getmessage());
        // }
    }

    public function redemptionrejectmail($email, $data)
    {
        try {
            $ref_code = (string) rand(100000000, 999999999);
            $currentDate = Carbon::now()->format('Y-m-d');
            $maildata = [
                'title' => 'Redeemtion Update',
                'amount' => $data->amount,
                'coupon_id' => $data->coupon->coupon_code,
                'status' => $data->status,
                'first_name' => $email->first_name,
                'last_name' => $email->last_name,
                'referance_code' => $ref_code,
                'currentDate' => $currentDate,
                'reason' => $data->admin_comment,
            ];
            Mail::to($email->email_id)->send(new redeemptionReject($maildata, $ref_code));
        } catch (Throwable $t) {
            Log::error('mail sending fail: ' . $t->getmessage());
        }
    }
}
