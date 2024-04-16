<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\RedeemptionReqMail;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Qr;
use App\Models\RedemptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;
use Throwable;

class RedemptionController extends Controller
{
    //this code is for coupon redemption

    public function coupon(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'coupon_code' => 'required',
        ]);

        if ($validateData->fails()) {
            $message = $validateData->errors();
            return response()->json(responseData(null, $message, false));
        } else {

            $id = auth('sanctum')->user()->id;


            $qr_id = Coupon::with('couponRequest')->where('coupon_code', $request->coupon_code)->first();
            if ($qr_id->status == 'active') {
                $entity = new RedemptionRequest();
                $entity->customer_id = $id;
                $entity->request_date_time    = Carbon::now();
                $entity->coupon_id = $qr_id->id;
                $entity->amount = $qr_id->couponRequest->amount;
                $entity->save();

                if ($entity->save()) {
                    $qr_id->status = 'used';
                    $qr_id->save();

                    if ($qr_id->save()) {
                        $ref_code = (string) rand(100000000, 999999999);
                        $timestamp = Carbon::now();
                        $notification = new Notification();
                        $notification->customer_id = $id;
                        $notification->message = "Your redemption request for Presto Plast India Rewards App has been generated. Ref No: $ref_code. You will be notified once approved/rejected. Thank You!";
                        $notification->date_time = $timestamp;
                        $notification->is_read = "unread";
                        $notification->notification_type = "system_gen";
                        $notification->long_content = "Thank you for submitting your redemption request. We have received it and will process it shortly. You can track the status in your PrestoRewards app.";
                        $notification->save();
                    }

                    $req_id = $entity->id;
                    $email_id = Customer::where('id', $id)->first();
                    if($email_id->email_id)
                    {
                        $this->redemptionmail($email_id,$entity,$qr_id);
                    }
                    $data = RedemptionRequest::where('id', $req_id)->first();
                    return response()->json(responseData($data, "wait for reedmption"));
                }
            } elseif ($qr_id->status == 'used') {
                return response()->json(responseData(null, "qr is already used", false));
            } elseif ($qr_id->status == 'rejected') {
                return response()->json(responseData(null, "qr is already rejected", false));
            } else {
                return response()->json(responseData(null, "qr is expired", false));
            }
        }
    }

    //this code is for view coupan data and validate

    public function coupondata(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'coupon_code' => 'required',
        ]);

        if ($validateData->fails()) {
            $message = $validateData->errors();
            return response()->json(responseData(null, $message, false));
        } else {
            $data = Coupon::with('couponRequest')->where('coupon_code', $request->coupon_code)->first();
            if ($data == null) {
                return response()->json(responseData(null, "invalid coupon", false));
            } elseif ($data->status == 'active') {
                $formattedData = [
                    'id' => $data->id,
                    'coupon_code' => $data->coupon_code,
                    'coupon_request_id' => $data->coupon_request_id,
                    'amount' => $data->couponRequest->amount,
                    'company_id' => $data->couponRequest->company_id,
                    'customer_id' => $data->customer_id,
                    'status' => $data->status,
                ];

                return response()->json(responseData($formattedData, "Do you want to redeem?"));
            } elseif ($data->status == 'used') {
                return response()->json(responseData(null, "qr is already used", false));
            } elseif ($data->status == 'rejected') {
                return response()->json(responseData(null, "qr is already rejected", false));
            } else {
                return response()->json(responseData(null, "qr is expired", false));
            }
        }
    }

    public function redemtionhistory()
    {
        $id = auth('sanctum')->user()->id;
        $redemptionhistory = RedemptionRequest::with('coupon')->where('customer_id', $id)->orderBy('created_at', 'desc')->get();
        if ($redemptionhistory->isEmpty()) {
            return response()->json(responseData(null, "No history found", false));
        } else {
            return response()->json(responseData($redemptionhistory, "QR redemption history"));
        }
    }

    public function redemptionmail($email,$data,$qr_id)
    {
        try{
            $ref_code = (string) rand(100000000, 999999999);
            $currentDate = Carbon::now()->format('Y-m-d');
            $maildata = [
                'title' => 'Redeemtion Request Generated',
                'amount' => $data->amount,
                'coupon_id' => $qr_id->coupon_code,
                'first_name' => $email->first_name,
                'last_name' => $email->last_name,
                'referance_code' => $ref_code,
                'currentDate' => $currentDate


            ];
            Mail::to($email->email_id)->send(new RedeemptionReqMail($maildata, $ref_code));
        }catch(Throwable $t)
        {
            Log::error('mail sending fail: ' . $t->getmessage());
        }
    }
}
