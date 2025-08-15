<?php

namespace App\Jobs;

use App\Models\HistoryView;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InsertViewedProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //\Log::info(json_encode($this->data));

        $history = HistoryView::where('user_id', $this->data['user_id'])
            ->where('product_id', $this->data['product_id'])
            ->first();

        if (!empty($history)) {
            $history->updated_at = $this->data['time'];
            $history->save();
        } else {
            $this->data['created_at'] = $this->data['time'];
            $this->data['updated_at'] = $this->data['time'];
            $id = HistoryView::create($this->data);
        }
    }
}
