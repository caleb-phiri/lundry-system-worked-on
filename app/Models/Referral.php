<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id', 'referred_id', 'referral_code', 'status',
        'reward_amount', 'reward_issued', 'reward_issued_at'
    ];

    protected $casts = [
        'reward_issued' => 'boolean',
        'reward_issued_at' => 'datetime',
        'reward_amount' => 'decimal:2',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function complete()
    {
        $this->status = 'completed';
        $this->save();

        // Issue reward to referrer
        if (!$this->reward_issued) {
            $this->referrer->wallet->credit(
                $this->reward_amount,
                "Referral bonus for {$this->referred->name}"
            );
            
            $this->reward_issued = true;
            $this->reward_issued_at = now();
            $this->save();
        }
    }
}