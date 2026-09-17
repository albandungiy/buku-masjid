<?php

namespace App\Models;

use App\Traits\Models\ConstantsGetter;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use ConstantsGetter;

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    protected $fillable = [
        'book_id', 'category_id', 'title', 'amount', 'asnaf_detail', 'description', 'status_id',
        'transaction_id', 'distribution_date', 'creator_id', 'approved_id', 'approved_at',
    ];

    protected $casts = [
        'asnaf_detail' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Same reasoning as Donation::netTransaction() — bypass ForActiveBook so this relation
    // still resolves when the viewer's active book differs from the distribution's fund book.
    public function transaction()
    {
        return $this->belongsTo(Transaction::class)->withoutGlobalScope('forActiveBook');
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getStatusAttribute(): string
    {
        return match ($this->status_id) {
            self::STATUS_APPROVED => __('distribution.status_approved'),
            self::STATUS_REJECTED => __('distribution.status_rejected'),
            default => __('distribution.status_pending'),
        };
    }

    public function getAmountStringAttribute()
    {
        return format_number($this->amount);
    }
}
