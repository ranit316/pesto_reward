<?php

namespace App\Listeners;

use App\Events\PushNotification;
use App\Models\AppSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PushNotification $event)
    {
        //
        $message = $event->push_notification;
        $cus_id = $event->cus_id;
        //$device_id = AppSetting::where('customer_id',$cus_id)->latest()->first();
        $firebaseToken = AppSetting::where('customer_id', $cus_id)->latest()->pluck('mobile_id')->filter()->unique();
        //$firebaseToken = $reg_ids->toArray();

        $body = 'Congratulations! Your redemption request for Presto Plast India Rewards App has been approved. Thank You!';

        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $message,
                "body" => $body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        curl_close($ch);
        //$event->responsedata = json_decode($response);
        //return $event->responsedata;
        return back()->with('success', 'Notification send successfully.');
    }
}
