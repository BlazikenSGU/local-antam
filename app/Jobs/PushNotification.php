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

class PushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notification = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
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
        $registration_ids = [];

        if ($this->notification->chanel == 2)
            $registration_ids[] = $this->notification->to_user->fcm_token;
        else {
            $all_user = CoreUsers::select('fcm_token')->get();
            foreach ($all_user as $u) {
                if ($u->fcm_token != '')
                    $registration_ids[] = $u->fcm_token;
            }
        }

        if (!empty($registration_ids)) {
            $firebase = new Firebase();
            //$result = $firebase->send($registration_ids, $this->notification);

            $msg = [
                "registration_ids" => $registration_ids,
                "notification"     => [
                    "title" => $this->notification->title,
                    'message' => $this->notification->content,
                    'body' => $this->notification->content,
                    'sound'             => "default",
                ],

                "data"             => $this->notification->toArray()
            ];

            $result = $firebase->sendPushNotification($msg);
        }
    }
}
