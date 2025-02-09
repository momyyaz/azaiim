<?php

namespace App\Model;

use App\CPU\Helpers;
use App\Model\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $casts = [
        'user_id' => 'integer',
        'brand_id' => 'integer',
        'min_qty' => 'integer',
        'published' => 'integer',
        'tax' => 'float',
        'unit_price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'current_stock' => 'integer',
        'free_shipping' => 'integer',
        'featured_status' => 'integer',
        'refundable' => 'integer',
        'featured' => 'integer',
        'flash_deal' => 'integer',
        'seller_id' => 'integer',
        'purchase_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shipping_cost' => 'float',
        'multiply_qty' => 'integer',
        'temp_shipping_cost' => 'float',
        'is_shipping_cost_updated' => 'integer'
    ];

    public function translations()
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    public function scopeActive($query)
    {
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        $digital_product_setting = BusinessSetting::where('type', 'digital_product')->first()->value;

        if (!$digital_product_setting) {
            $product_type = ['physical'];
        } else {
            $product_type = ['digital', 'physical'];
        }

        return $query->when($brand_setting, function ($q) {
            $q->whereHas('brand', function ($query) {
                $query->where(['status' => 1]);
            });
        })->when(!$brand_setting, function ($q) {
            $q->whereNull('brand_id');
        })->where(['status' => 1])->orWhere(function ($query) {
            $query->whereNull('brand_id')->where('status', 1);
        })->SellerApproved()->whereIn('product_type', $product_type);
    }

    public function scopeSellerApproved($query)
    {
        $query->whereHas('seller', function ($query) {
            $query->where(['status' => 'approved']);
        })->orWhere(function ($query) {
            $query->where(['added_by' => 'admin', 'status' => 1]);
        });

    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeStatus($query)
    {
        return $query->where('featured_status', 1);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'seller_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'user_id');
    }

    public function rating()
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, product_id'))
            ->whereNull('delivery_man_id')
            ->groupBy('product_id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }


    public function order_delivered()
    {
        return $this->hasMany(OrderDetail::class, 'product_id')
            ->where('delivery_status', 'delivered');

    }

    public function wish_list()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getNameAttribute($name)
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/seller')) {
            return $name;
        }
        return $this->translations[0]->value ?? $name;
    }

    public function getDetailsAttribute($detail)
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/seller')) {
            return $detail;
        }
        return $this->translations[1]->value ?? $detail;
    }
    
    public function getDiscountAttribute($val) {
        if (gettype($this['category_ids']) == 'array') {
            $product_category = \App\Model\Category::find($this['category_ids'][0]->id);
        } else {
            $product_category = \App\Model\Category::find(json_decode($this['category_ids'])[0]->id);
        }
        //dd( $val, filter_var($val, FILTER_VALIDATE_INT), $val > 0);
        $val = filter_var($val, FILTER_VALIDATE_FLOAT);
        
        // case 1 : no discount in both product and category
        // case 2 : discount in product only
        // case 3 : discount in category only
        // case 4 : discount in both of them


        
        if ($product_category->discount != null) {
            // هنا بشوف فى خصم فى القسم ولا لا بس قبل ما اطبقه بشوف الاول المنتج نفسه عليه خصم ولا لا لو عليه خصم فبرجع خصم المنتج لانه اعلى اولوية لو المنتج مش عليه خصم برجع الخصم بتاع القسم
            if($val > 0) {
                
                return $val;
            } else {
               
                return $product_category->discount;
                
            }
            
        } else  {
           
            return $val;
        }

    }
    
    public function getDiscountTypeAttribute($val) {
        // نفس الفانكشن اللى فوق بس لنوع الخصم
        if (gettype($this['category_ids']) == 'array') {
            $product_category = \App\Model\Category::find($this['category_ids'][0]->id);
        } else {
            $product_category = \App\Model\Category::find(json_decode($this['category_ids'])[0]->id);
        }

        
        // case 1 : no discount in both product and category
        // case 2 : discount in product only
        // case 3 : discount in category only
        // case 4 : discount in both of them


        
        if ($product_category->discount != null) {
            // هنا بشوف فى خصم فى القسم ولا لا بس قبل ما اطبقه بشوف الاول المنتج نفسه عليه خصم ولا لا لو عليه خصم فبرجع خصم المنتج لانه اعلى اولوية لو المنتج مش عليه خصم برجع الخصم بتاع القسم
            if($this->getAttributes()['discount'] > 0) {
                // case 4
                return $val;
            } else {
                // case 3
                return $product_category->discount_type;
            }
            
        } else  {
            // case 1 and 2
            // يعنى لو مفيش خصم فى القسم هرجع الخصم بتاع المنتج. سواء كان فى خصم اصلا فى المنتج ولا لا هيرجع برضو يعنى لو بقيمة هترجع القيمة لو بصفر هترجع بصفر
            return $val;
        }

    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')) {
                    return $query->where('locale', App::getLocale());
                } else {
                    return $query->where('locale', Helpers::default_lang());
                }
            }, 'reviews'=>function($query){
                $query->whereNull('delivery_man_id');
            }])->withCount(['reviews'=>function($query){
                $query->whereNull('delivery_man_id');
            }]);
        });
    }
}
