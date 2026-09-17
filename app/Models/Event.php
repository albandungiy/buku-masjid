<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'book_id', 'title', 'description', 'location', 'start_date', 'end_date', 'color_code', 'creator_id',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => __('app.system')]);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getStartDateOnlyAttribute()
    {
        return Carbon::parse($this->start_date)->isoFormat('D MMMM Y');
    }
}
