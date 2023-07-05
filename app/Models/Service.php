<?php

namespace App\Models;

use App\Exceptions\DuplicateFavoriteException;
use App\Exceptions\DuplicateServiceException;
use App\Exceptions\FavoriteNotFoundException;
use App\Exceptions\ServiceNotFoundInCart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Service extends Model implements Searchable
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getSearchResult(): SearchResult
     {
        $url = route('client.service.one', $this->id);

         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->service_title,
            $url
         );
     }

    /**
     * Get the vendor that owns the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get all of the categories for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the status associated with the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne(ServiceStatus::class);
    }

    /**
     * Get all of the orders for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the service_pricing for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function service_pricing()
    {
        return $this->hasMany(ServicePricing::class);
    }

    /**
     * Get all of the service_images for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function service_images()
    {
        return $this->hasMany(ServiceGallery::class);
    }

    /**
     * Get all of the reviews for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all of the favorites for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorites::class);
    }

    /**
     * Get all of the messages for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function message()
    {
        return $this->hasManyThrough(Order::class, Message::class);
    }

    public function getCategory($id)
    {
        return Category::find($id);
    }

    public function getServiceStatus($id)
    {
        return ServiceStatus::find($id);
    }

    public function getCoverImage(string $image)
    {
        return Storage::disk('service')->url('cover_image/'.$image);
    }

    public function getImage(string $image)
    {
        return Storage::disk('service')->url('images/'.$image);
    }

    public function isInUserFavorites(?User $user)
    {
        if (!$user) {
            return false;
        }

        return Favorites::where('user_id', $user->id)->where('service_id', $this->id)->exists();
    }

    public function addToFavorites(User $user)
    {
        if ($this->isInUserFavorites($user)) {
            throw new DuplicateFavoriteException;
        }
        Favorites::create([
            'user_id' => $user->id,
            'service_id' => $this->id
        ]);
    }

    public function removeFromFavorites(User $user)
    {
        $dataToDelete = Favorites::where([
            'user_id' => $user->id,
            'service_id' => $this->id
        ])->first();

        if ($dataToDelete) {
            $dataToDelete->delete();
        } else {
            throw new FavoriteNotFoundException;
        }
    }

    public function isInCart(?User $user)
    {
        if(!$user) {
            return false;
        }

        return Cart::where('user_id', $user->id)->where('service_id', $this->id)->exists();
    }

    public function addServiceToCart(User $user)
    {
        if($this->isInCart($user)) {
            throw new DuplicateServiceException;
        }
        Cart::create([
            'user_id' => $user->id,
            'service_id' => $this->id
        ]);
    }

    public function removeFromCart(User $user)
    {
        $dataToDelete = Cart::where([
            'user_id' => $user->id,
            'service_id' => $this->id,
        ])->first();

        if ($dataToDelete) {
            $dataToDelete->delete();
        } else {
            throw new ServiceNotFoundInCart;
        }
    }

    public function pricingIsInCart(?User $user, ?ServicePricing $servicePricing)
    {
        if(!$user || !$servicePricing) {
            return false;
        }

        return Cart::where('user_id', $user->id)->where('service_id', $this->id)->where('service_pricing_id', $servicePricing->id)->exists();
    }

    public function addServicePricingToCart(User $user, ServicePricing $servicePricing)
    {
        if($this->isInCart($user, $servicePricing)) {
            throw new DuplicateServiceException;
        }
        Cart::create([
            'user_id' => $user->id,
            'service_id' => $this->id,
            'service_pricing_id' => $servicePricing->id
        ]);
    }

    public function removePricingFromCart(User $user, ServicePricing $servicePricing)
    {
        $dataToDelete = Cart::where([
            'user_id' => $user->id,
            'service_id' => $this->id,
            'service_pricing_id' => $servicePricing->id
        ])->first();

        if ($dataToDelete) {
            $dataToDelete->delete();
        } else {
            throw new ServiceNotFoundInCart;
        }
    }
}
