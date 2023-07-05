<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
      'f_name',
      'l_name',
      'email',
      'password',
      'phone_number',
      'avatar',
      'fb_id',
      'google_id',
      'phone_verification_code',
      'email_verified_at',
      'is_suspended',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
      'password',
      'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'email_verified_at' => 'datetime',
      'is_suspended' => 'bool'
    ];

    /**
     * Get the vendor associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vendor()
    {
      return $this->hasOne(Vendor::class);
    }

    /**
     * Get all of the orders for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the events for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
      return $this->hasMany(Event::class);
    }

    /**
     * Get all of the reviews for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
      return $this->hasMany(Review::class);
    }

    /**
     * Get all of the customerReviews for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerReviews()
    {
        return $this->hasMany(CustomerReview::class);
    }

    /**
     * Get all of the eventPrograms for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventPrograms()
    {
        return $this->hasMany(EventProgram::class);
    }
    public function isVendor()
    {
      if ($this->vendor()->exists()) {
         return true;
      }

      return false;
    }

    /**
     * Get all of the userRoles for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Get all of the favorites for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
      return $this->hasMany(Favorites::class);
    }

    public function getAvatar(?string $filename)
    {
      $image = $filename ? $filename : 'user.png';
      return Storage::disk('user')->url('avatar/'.$image);
    }

    public function hasAssignedRoles()
    {
       $role = UserRole::where('user_id', $this->id)->first();
       if ($role) {
          return true;
       }

       return false;
    }

    public function hasUnreadMessages()
    {
      $messages = Messages::where('is_read', false)
         ->where(function(Builder $query) {
            return $query->where('sent_to', $this->id);
         })
         ->get();
      if ($messages->count() > 0) {
         return ['status' => true, 'count' => $messages->count()];
      }

      return false;
    }
}
