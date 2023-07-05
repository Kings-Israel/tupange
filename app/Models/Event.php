<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'status' => 'Active'
    ];

    protected $casts = [
        'event_start_date' => 'datetime',
        'event_end_date' => 'datetime',
        'isCorporate' => 'bool'
    ];

    public function getEventCoverImage(string $image)
    {
        return Storage::disk('event')->url('poster/'.$image);
    }

    /**
     * Get the user that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the event_tasks for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function event_tasks()
    {
        return $this->hasMany(EventTask::class);
    }

    /**
     * Get all of the gifts for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    /**
     * Get all of the event_guests for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function event_guests()
    {
        return $this->hasMany(EventGuestDetail::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the budget for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function budget()
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Get all of the register for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registers()
    {
      return $this->hasMany(Register::class);
    }

    /**
     * Get all of the event_users for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function event_users()
    {
        return $this->hasMany(EventUser::class);
    }

    /**
     * Get all of the user_roles for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_roles()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Get the program associated with the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function program()
    {
        return $this->hasOne(EventProgram::class);
    }

    /**
     * Get all of the registrationTickets for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrationTickets()
    {
        return $this->hasMany(RegisterTicket::class);
    }

    public function getGeneralAdmissionGuestCount()
    {
      return $this->event_guests->where('ticket_title', 'General Admission')->count();
    }

    public function setEventStatus()
    {
        if ($this->event_start_date > now()) {
            $this->status = "Active";
        } elseif ($this->event_start_date <= now() && $this->event_end_date >= now()) {
            $this->status = "Live";
        } elseif ($this->event_end_date < now()) {
            $this->status = "Past";
        }

        $this->save();
    }

    public function getEventStatus()
    {
        $this->setEventStatus();
        return $this->status;
    }

    public function getCoverImage(string $image)
    {
        return Storage::disk('service')->url('cover_image/'.$image);
    }
}
