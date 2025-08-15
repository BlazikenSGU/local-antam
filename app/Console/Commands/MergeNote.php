<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\PushNotification;
use App\Models\Location\Province;
use App\Models\Product;
use App\Models\ProductContactInfo;
use App\Models\ProductNote;
use App\Models\ThienMinh\District;
use App\Models\ThienMinh\Lands;
use App\Models\ThienMinh\Notes;
use App\Models\ThienMinh\Ward;
use App\ReportsHourly;
use App\Utils\Firebase;
use App\Utils\GoogleMaps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class MergeNote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lands:merge-note';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::select('id')
            //->limit(1000)
            ->get();

        foreach ($products as $product) {
            $new_notes = ProductNote::where('product_id', $product->id)->count();
            if ($new_notes > 0) {
                continue;
            }

            $old_notes = Notes::where('land_id', $product->id)->get();
            $aNote = [];
            foreach ($old_notes as $old_note) {
                if (empty($old_note->content))
                    continue;

                $aNote[] = [
                    'product_id' => $product->id,
                    'user_id'    => $old_note->user_id,
                    'content'    => $old_note->content,
                    'merged'     => $old_note->merged,
                    'created_at' => $old_note->created,
                ];
            }
            if ($aNote)
                ProductNote::insert($aNote);
        }
    }
}
