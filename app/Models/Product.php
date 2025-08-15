<?php

namespace App\Models;

use App\Models\Location\Street;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel implements Jsonable
{
    use SoftDeletes;

    const STATUS = [
        ['id' => 1, 'name' => 'Hiển thị'],
        ['id' => 2, 'name' => 'Ẩn'],
    ];
    protected $table = 'lck_product';
    protected $fillable = [
        'company_id',
        'id',
        'type',
        'specifications',
        'product_type_id',//danh mục sp
        'product_type_ids',//danh mục sp liên quan
        'product_code',
        'title',//tiêu đề
        'slug',
        'description',// mô tả
        'intro',
        'shipping_info',// thông tin vận chuyển
        'contact_info',// liên hệ với chúng tôi
        'detail',

        'seo_title',
        'seo_descriptions',
        'seo_keywords',

        'price',// giá đăng bán
        'price_old',// giá cũ

        'status',// trạng thái
        'thumbnail_id',// id hình đại diện

        'image_fb_file_id',// id hình đại diện fb

        'user_id',
        'user_id_updated',
        'created_at',
        'updated_at',
        'inventory_management',
        'inventory',
        'inventory_policy',

        'is_best_sell',
        'is_new_arrival',
        'is_featured',
        'is_sale',
        'total_color',
        'can_index',
        'is_multilevel',
        'percent_discount',
    ];
    protected $hidden = ['pivot'];
    protected $appends = ['status_name'];

    public static function get_by_id($product_id)
    {
        return self::find($product_id);
    }

    public static function get_by_slug($slug)
    {
        return self::where("slug", $slug)->first();
    }

    public static function get_validation_admin()
    {
        return [
            'product_type_id'  => 'required|integer|exists:lck_product_type,id',
            'product_type_ids' => 'required|array',
            'product_code'     => 'nullable|string',
            'specifications'   => 'nullable|string',
            'type'             => 'nullable|integer',
            'title'            => 'required|string',
            'slug'             => 'nullable|string',
            'description'      => 'nullable|string',
            'intro'            => 'nullable|string',
            'shipping_info'    => 'nullable|string',
            'contact_info'     => 'nullable|string',
            'detail'           => 'nullable|string',

            'seo_title'        => 'nullable|string',
            'seo_descriptions' => 'nullable|string',
            'seo_keywords'     => 'nullable|string',

            'price'     => 'required|integer|min:0',
            'price_old' => 'nullable|integer|min:0',

            'status'           => 'required|integer',
            'thumbnail_id'     => 'nullable|integer|exists:lck_files,id',
            'image_fb_file_id' => 'nullable|integer|exists:lck_files,id',

            'user_id'              => 'nullable|integer',
            'percent_discount'     => 'nullable|integer',
            'user_id_updated'      => 'nullable|integer',
            'image_ids'            => 'nullable|array',
            //
            'inventory_management' => 'required|in:0,1',
            'inventory'            => 'nullable|integer|min:0',
            'inventory_policy'     => 'nullable|in:0,1',

            'is_sale'        => 'nullable|in:0,1',
            'is_best_sell'   => 'nullable|in:0,1',
            'is_new_arrival' => 'nullable|in:0,1',
            'is_featured'    => 'nullable|in:0,1',
            'total_color'    => 'nullable|integer',
            'can_index'      => 'nullable|in:0,1',
            'is_multilevel'  => 'nullable|in:0,1',
        ];
    }

    public static function update_inventory($params)
    {
        $params = array_merge(array(
            'product_id' => null,
            'quantity'   => 0,
        ), $params);

        if ($params['quantity'] > 0)
            return self::where('id', $params['product_id'])
                ->where('inventory_management', 1)
                ->increment('inventory', $params['quantity']);
        else
            return self::where('id', $params['product_id'])
                ->where('inventory_management', 1)
                ->decrement('inventory', abs($params['quantity']));
    }

    public static function change_inventory($params)
    {
        $params = array_merge(array(
            'product_id' => null,
        ), $params);

        $check_variation = ProductVariation::where('product_id', $params['product_id'])
            ->where('inventory_management', 1)
            ->count();

        if (!$check_variation) return false;

        $totalinventory = ProductVariation::get_inventory([
            'product_id' => $params['product_id'],
        ]);

        return self::where('id', $params['product_id'])
//            ->where('inventory_management', 1)
            ->update([
                'inventory' => $totalinventory,
            ]);

    }

    public static function get_cart_data($params = [])
    {
        $params = array_merge([
            'cart'           => null,
            'cart_processed' => null,
        ], $params);

        $cart = $params['cart'];
        $cart_processed = $params['cart_processed'];

        $cart_data_return = [];
        if (empty($cart))
            return [
                'items' => [],
                'total' => 0,
            ];

        $products = self::get_by_where([
            'product_ids' => $cart_processed['product_ids'],
            'status'      => 1,
            'limit'       => null,
            'pagin'       => false,
        ], ['thumbnail']);

        if (!count($products))
            return [
                'items' => [],
                'total' => 0,
            ];

        $products = $products->groupBy('id')->toArray();

        $product_variations = [];

        if (!empty($cart_processed['product_variation_ids'])) {
            $product_variation_db = ProductVariation::whereIn('id', $cart_processed['product_variation_ids'])->get();
            $product_variation_db = count($product_variation_db) ? $product_variation_db->toArray() : [];

            foreach ($product_variation_db as $v) {
                $product_variations[$v['id']] = $v;
            }
        }

        $total_price = $price_reduce = 0;

        foreach ($cart as $k => $v) {
            if (!isset($products[$v['product_id']])) continue;

            $_product = array_merge($products[$v['product_id']][0], $v);

            $_product['picture'] = (isset($_product['thumbnail']['file_src']) ? $_product['thumbnail']['file_src'] : null);
            $_product['picture_path'] = (isset($_product['thumbnail']['file_path']) ? $_product['thumbnail']['file_path'] : null);

            if ($v['product_variation_id'] && isset($product_variations[$v['product_variation_id']]) && $v['product_id'] == $product_variations[$v['product_variation_id']]['product_id']) {

                $product_variation = $product_variations[$v['product_variation_id']];

                if ($product_variation['price'] > 0) {
                    $_product['price'] = $product_variation['price'];
                    $_product['price_old'] = $_product['price_old'] > $product_variation['price'] ? $_product['price_old'] : $product_variation['price'];
                }

                $gallery = !empty($product_variation['gallery']) ? json_decode($product_variation['gallery'], true) : [];
                $_product['picture'] = $gallery ? \App\Utils\Links::ImageLink($gallery[0]['file_path'], true) : $_product['picture'];
                $_product['picture_path'] = $gallery ? $gallery[0]['file_path'] : $_product['picture_path'];
                $_product['product_code'] = $product_variation['product_code'] ? $product_variation['product_code'] : $_product['product_code'];
                $_product['inventory_management'] = $product_variation['inventory_management'];
                $_product['inventory_policy'] = $product_variation['inventory_policy'];
                $_product['inventory'] = $product_variation['inventory'];
                $_product['product_variation_id'] = $product_variation['id'];
                $_product['product_variation_name'] = $product_variation['name'];

            } else {
                $_product['product_variation_id'] = null;
                $_product['product_variation_name'] = null;
            }

            $_product['has_out_of_stock'] = 0;
            $_product['buy_out_of_stock'] = 0;
            if ($_product['inventory_management']) {
                if ($_product['inventory_policy']) {
                    if ($_product['inventory'] < $_product['quantity']) {
                        $_product['buy_out_of_stock'] = 1;
                    }
                } else {
                    if ($_product['inventory'] < 1) {
                        $_product['has_out_of_stock'] = 1;
                        $_product['price_old'] = $_product['price'] = 0;
                    } elseif ($_product['inventory'] < $_product['quantity']) {
                        $_product['quantity'] = $v['quantity'] = $_product['inventory'];
                    }
                }
            }

            $total_price += $_product['price'] * $v['quantity'];

            $cart_data_return[$k] = $_product;
        }

        return [
            'items' => $cart_data_return,
            'total' => $total_price,
        ];
    }

    public static function get_by_where($params = [], $with = [])
    {
        $params = array_merge(
            [
                'keywords'             => null,
                'status'               => null,
                'multi_status'         => null,
                'product_type_id'      => null,
                'category_ids'         => null,
                'price_from'           => null,
                'price_to'             => null,
                'skip_product_ids'     => null,
                'product_ids'          => null,
                'user_id'              => null,
                'get_favorite'         => false,
                'user_id_favorite'     => null,
                'pagin_path'           => null,
                'limit'                => 500,
                'pagin'                => true,
                'order_by'             => null,
                'order_by_direction'   => 'ASC',
                'sort'                 => null,
                'get_variation'        => null,
                'inventory_management' => null,
                'is_best_sell'         => null,
                'is_new_arrival'       => null,
                'is_sale'              => null,
                'is_featured'          => null,
                'price_range'          => null,

            ],
            $params
        );

        \DB::enableQueryLog();

        $select = [
            'lck_product.id',
            'lck_product.product_type_id',
            'lck_product.specifications',
            'lck_product.product_code',
            'lck_product.type',
            'lck_product.title',
            'lck_product.description',
            'lck_product.slug',
            'lck_product.price',
            'lck_product.price_old',
            'lck_product.percent_discount',
            'lck_product.status',
            'lck_product.thumbnail_id',
            'lck_product.inventory_management',
            'lck_product.inventory',
            'lck_product.inventory_policy',
            'lck_product.total_color',
            'lck_product.is_multilevel',
            'lck_product.deleted_at',
            'lck_product.created_at',
        ];


        if ($params['get_variation']) {
            $select = array_merge($select, [
                'lck_product_variation.id as product_variation_id',
                'lck_product_variation.name as product_variation_name',
                'lck_product_variation.price as product_variation_price',
                'lck_product_variation.product_code as product_variation_product_code',
                'lck_product_variation.inventory_management as product_variation_inventory_management',
                'lck_product_variation.inventory as product_variation_inventory',
                'lck_product_variation.inventory_policy as product_variation_inventory_policy',
                'lck_product_variation.gallery as product_variation_gallery',
            ]);
        }

        \DB::enableQueryLog();

        $products = self::select($select);
        $products->with(['thumbnail']);

        $products->where('lck_product.company_id', config('constants.company_id'));

        if ($params['status'])
            $products->where('lck_product.status', $params['status']);

        if ($params['keywords'])
            $products->whereRaw("(lck_product.title like '%{$params['keywords']}%' OR lck_product.product_code like '%{$params['keywords']}%')");

        if ($params['multi_status'])
            $products->whereIn('lck_product.status', $params['multi_status']);

        if ($params['product_type_id']) {
            $params['product_type_id'] = is_array($params['product_type_id']) ? $params['product_type_id'] : [$params['product_type_id']];
            $products->leftJoin('lck_products_types', 'lck_product.id', '=', 'lck_products_types.product_id');
            $products->whereIn('lck_products_types.type_id', $params['product_type_id']);
        }

        if ($params['category_ids']) {
            $cate_ids = !is_array($params['category_ids']) ? explode(',', $params['category_ids']) : $params['category_ids'];

            $products->whereIn('lck_products_types.type_id', $cate_ids);
//            $products->whereHas('categories', function ($query) use ($cate_ids) {
//                $query->whereIn('lck_product_category.id', $cate_ids);
//            })->get();
        }
        if ($params['price_from'])
            $products->where('lck_product.price', '>=', $params['price_from']);

        if ($params['price_to'])
            $products->where('lck_product.price', '<=', $params['price_to']);

        if ($params['skip_product_ids']) {
            $skip_product_ids = !is_array($params['skip_product_ids']) ? explode(',', $params['skip_product_ids']) : $params['skip_product_ids'];
            $products->whereNotIn('lck_product.id', $skip_product_ids);
        }
        if ($params['product_ids']) {
            $product_ids = !is_array($params['product_ids']) ? explode(',', $params['product_ids']) : $params['product_ids'];
            $products->whereIn('lck_product.id', $product_ids);
        }
        if ($params['user_id'])
            $products->where('lck_product.user_id', $params['user_id']);

        if ($params['get_favorite'] && $params['user_id_favorite']) {
            $products->whereRaw('lck_product.id IN (select product_id from lck_favorite where user_id = ? )', $params['user_id_favorite']);
        }

        if ($params["inventory_management"] == 1 && $params['get_variation'])
            $products->whereRaw("((lck_product.inventory_management = 1 AND lck_product_variation.inventory_management IS NULL) OR lck_product_variation.inventory_management = 1)");

        if ($params['is_best_sell'] !== null) {
            $products->where('lck_product.is_best_sell', $params['is_best_sell']);
        }

        if ($params['is_new_arrival'] !== null) {
            $products->where('lck_product.is_new_arrival', $params['is_new_arrival']);
        }

        if ($params['is_sale'] !== null) {
            $products->where('lck_product.is_sale', $params['is_sale']);
        }


        if ($params['is_featured'] !== null) {
            $products->where('lck_product.is_featured', $params['is_featured']);
        }
        if ($params['price_range']) {
            $params['price_range'] = str_replace(',', '', $params['price_range']);
            $params['price_range'] = str_replace('đ', '', $params['price_range']);
            $params['price_range'] = explode('-', $params['price_range']);

            if (isset($params['price_range'][0]))
                $products->where('lck_product.price', '>=', (int)$params['price_range'][0]);

            if (isset($params['price_range'][1]))
                $products->where('lck_product.price', '<=', (int)$params['price_range'][1]);
        }

        $aSort = [
            'name_a_z'         => [
                'column'    => 'lck_product.title',
                'direction' => 'ASC'
            ],
            'name_z_a'         => [
                'column'    => 'lck_product.title',
                'direction' => 'DESC'
            ],
            'recently_changed' => [
                'column'    => 'lck_product.updated_at',
                'direction' => 'DESC'
            ],
            'newest'           => [
                'column'    => 'lck_product.created_at',
                'direction' => 'DESC'
            ],
            'oldest'           => [
                'column'    => 'lck_product.created_at',
                'direction' => 'ASC'
            ],
            'price_low_high'   => [
                'column'    => 'lck_product.price',
                'direction' => 'ASC'
            ],
            'price_high_low'   => [
                'column'    => 'lck_product.price',
                'direction' => 'DESC'
            ],
            'status'           => [
                'column'    => 'lck_product.status',
                'direction' => 'ASC'
            ],
            'id_low_high'      => [
                'column'    => 'lck_product.id',
                'direction' => 'ASC'
            ],
            'id_high_low'      => [
                'column'    => 'lck_product.id',
                'direction' => 'DESC'
            ],
        ];

        if (isset($aSort[$params['sort']])) {
            $products->orderBy($aSort[$params['sort']]['column'], $aSort[$params['sort']]['direction']);
        } else if ($params['order_by']) {
            $products->orderBy($params['order_by'], $params['order_by_direction']);
        }

        if ($params['get_variation']) {
            $products->leftJoin('lck_product_variation', "lck_product_variation.product_id", "lck_product.id")
                ->groupBy(['lck_product.id', 'lck_product_variation.id']);
        } else {
            $products->groupBy(['lck_product.id']);
        }

        if ($params['pagin']) {
            $products = $products->paginate($params['limit'])
                ->withPath($params['pagin_path']);
        } else {
            if ($params['limit'])
                $products->limit($params['limit']);
            $products = $products->get();
        }

        return $products;
    }

    public static function update_total_color($params)
    {
        $params = array_merge([
            'product_id' => null,
        ], $params);

        $total_color = ProductVariation::count_by_variation_value($params['product_id']);

        self::where('id', $params['product_id'])
            ->update([
                'total_color' => $total_color,
            ]);
    }

    public function getStatusnameAttribute()
    {
        foreach (self::STATUS as $v)
            if ($this->status == $v['id'])
                return $v['name'];
    }

    public function product_type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id')
            ->select(['id', 'name', 'slug']);
    }

    public function owner()
    {
        return $this->belongsTo(CoreUsers::class, 'user_id', 'id')->select(['id', 'fullname', 'email', 'phone']);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Files::class)->select(['id', 'file_path']);
    }

    public function images()
    {
        return $this->belongsToMany(Files::class, 'lck_product_images', 'product_id', 'file_id')
            ->select(['lck_files.id', 'lck_files.file_path'])
            ->orderBy('lck_product_images.id', 'ASC');
    }

    public function images_extra()
    {
        return $this->belongsToMany(Files::class, 'lck_product_images_extra', 'product_id', 'file_id')
            ->select(['lck_files.id', 'lck_files.file_path'])->orderBy('priority');
    }

    function status_name()
    {
        foreach (self::STATUS as $v)
            if ($this->status == $v['id'])
                return $v['name'];
    }

    public function image_fb()
    {
        return $this->belongsTo(Files::class, 'image_fb_file_id', 'id')->select(['id', 'file_path']);
    }
}
