<?php

namespace App\Models;

use App\Traits\Models\ConstantsGetter;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use ConstantsGetter;

    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_FAILED = 2;

    const PAYMENT_METHOD_TRANSFER_BANK = 'transfer_bank';
    const PAYMENT_METHOD_E_WALLET = 'e_wallet';
    const PAYMENT_METHOD_TUNAI = 'tunai';

    protected $fillable = [
        'partner_id', 'book_id', 'date', 'amount', 'payment_method_code', 'status_id',
        'net_transaction_id', 'hak_amil_transaction_id', 'creator_id', 'confirmed_at',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Donations are listed across every Ziswaf book regardless of which book is currently
    // "active" (see docs/task-upz.md Fase 1 note), so these relations must bypass
    // Transaction's ForActiveBook global scope — otherwise they silently return null
    // whenever the viewer's active book differs from the donation's fund book.
    public function netTransaction()
    {
        return $this->belongsTo(Transaction::class, 'net_transaction_id')->withoutGlobalScope('forActiveBook');
    }

    public function hakAmilTransaction()
    {
        return $this->belongsTo(Transaction::class, 'hak_amil_transaction_id')->withoutGlobalScope('forActiveBook');
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getStatusAttribute(): string
    {
        return match ($this->status_id) {
            self::STATUS_CONFIRMED => __('donation.status_confirmed'),
            self::STATUS_FAILED => __('donation.status_failed'),
            default => __('donation.status_pending'),
        };
    }

    public function getAmountStringAttribute()
    {
        return format_number($this->amount);
    }
}
