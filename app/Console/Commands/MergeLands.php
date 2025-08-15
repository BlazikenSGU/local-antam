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

class MergeLands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lands:merge';

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
        $districts = \App\Models\Location\District::where('province_id', '79')->get();

        $i = 0;
        foreach ($districts as $district) {
            //get only district 1,3
            if (!in_array($district->id, [760, 770]))
                continue;

            $ward_ids = \App\Models\Location\Ward::where('district_id', $district->id)->get()->pluck('id')->toArray();

            $lands = Lands::with('ward')
                //->limit(10)
                ->whereIn('ward_id', $ward_ids)
                ->get();

            foreach ($lands as $land) {

                if (Product::find($land->id))
                    continue;

                $land->ward->district;

                $data = [
                    'id'                => $land->id,
                    'description'       => $land->description,
                    'province_id'       => 79,
                    'district_id'       => isset($land->ward->district) ? $land->ward->district->district_id : null,
                    'ward_id'           => $land->ward_id,
                    'street_id'         => $land->street_id,
                    'apartment_number'       => $land->apartment_number,
                    'price'             => $land->price,
                    'price_type'        => 2,
                    'price_full'        => $land->price * 1000000000,
                    'length'        => $land->width,
                    'width'          => $land->length,
                    'area'              => $land->area,
                    'home_structure'    => (int)$land->structure,
                    'product_type_id'   => 1,
                    'legal_status'      => $land->description,
                    'basement_number'   => $land->basement,
                    'floor_number'      => $land->stories,
                    'mezzanine_number'  => $land->mezzanine,
                    'terrace'           => $land->terrace,
                    'status'            => $land->sold,
                    'status_censorship' => $land->status,
                    'user_id'           => $land->created_by,
                    'created_at'        => $land->created,
                    'updated_at'        => $land->modified,
                    'thumbnail_id'      => null,
                ];
                $data['address'] = $this->_getAddress($data);

                if ($land->status == Product::CENSORSHIP_APPROVED && $i < 900) {
                    //$data['location'] = GoogleMaps::getLatLong($data['address'], true);
                }

                $product = Product::create($data);
                $i++;

                if ($product->id) {
                    if ($land->phone) {
                        ProductContactInfo::create([
                            'product_id' => $product->id,
                            'type'       => $land->owner,
                            'name'       => $land->full_name,
                            'address'    => null,
                            'phone'      => $land->phone,
                            'phone1'     => $land->phone1,
                            'phone2'     => $land->phone2,
                        ]);

                        /*\App\Models\ProductImages::insert([
                            ['product_id' => $product->id, 'file_id' => 1,],
                            ['product_id' => $product->id, 'file_id' => 2,],
                            ['product_id' => $product->id, 'file_id' => 3,],
                        ]);

                        \App\Models\ProductImagesExtra::insert([
                            ['product_id' => $product->id, 'file_id' => 4,],
                            ['product_id' => $product->id, 'file_id' => 5,],
                        ]);

                        \App\Models\ProductExterior::insert([
                            ['product_id' => $product->id, 'exterior_id' => 1,],
                            ['product_id' => $product->id, 'exterior_id' => 2,],
                            ['product_id' => $product->id, 'exterior_id' => 3,],
                        ]);

                        \App\Models\ProductConvenience::insert([
                            ['product_id' => $product->id, 'convenience_id' => 1,],
                            ['product_id' => $product->id, 'convenience_id' => 2,],
                            ['product_id' => $product->id, 'convenience_id' => 3,],
                        ]);*/
                    }
                }
            }
        }
    }

    protected function _getAddress($params)
    {
        if (empty($params['province_id'])
            || empty($params['district_id'])
            || empty($params['ward_id'])
            || empty($params['street_id'])
            || empty($params['apartment_number']))
            return null;

        $province = \App\Models\Location\Province::findOrFail($params['province_id']);
        $district = \App\Models\Location\District::findOrFail($params['district_id']);
        $ward = \App\Models\Location\Ward::findOrFail($params['ward_id']);
        $street = \App\Models\Location\Street::findOrFail($params['street_id']);
        $address = $params['apartment_number'] . ' ' . $street->name . ', ' . $ward->name . ', ' . $district->name . ', ' . $province->name;
        return $address;
    }
}
