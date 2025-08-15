<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\CoreUsers;
use App\Utils\Firebase;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateNPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notification = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = Notification::create($this->notification);

        if ($notification) {
            $registration_ids = [];

            if ($notification->chanel == 2)
                if ($notification->to_user->fcm_token)
                    $registration_ids[] = $notification->to_user->fcm_token;
                else {
                    $all_user = CoreUsers::select('fcm_token')->get();
                    foreach ($all_user as $u) {
                        if ($u->fcm_token != '')
                            $registration_ids[] = $u->fcm_token;
                    }
                }

            if (!empty($registration_ids)) {
                $firebase = new Firebase();
                //$result = $firebase->send($registration_ids, $notification);

                $msg = [
                    "registration_ids" => $registration_ids,
                    "notification"     => [
                        "title" => $notification->title,
                    ],
                    "data"             => $notification->toArray()
                ];

                $result = $firebase->sendPushNotification($msg);
            }
        }
    }
}
