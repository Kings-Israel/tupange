<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gift extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getGiftImage(string $filename)
    {
        return Storage::disk('event')->url('gift/'.$filename);
    }

    /**
     * Get the event that owns the Gift
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
