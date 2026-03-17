<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'balance', 'lifetime_earnings', 'lifetime_spent'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'lifetime_earnings' => 'decimal:2',
        'lifetime_spent' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function credit($amount, $description, $metadata = null)
    {
        $this->balance += $amount;
        $this->lifetime_earnings += $amount;
        $this->save();

        return $this->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description,
            'status' => 'completed',
            'metadata' => $metadata,
            'reference' => 'WAL-' . uniqid()
        ]);
    }

    public function debit($amount, $description, $metadata = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $this->balance -= $amount;
        $this->lifetime_spent += $amount;
        $this->save();

        return $this->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'description' => $description,
            'status' => 'completed',
            'metadata' => $metadata,
            'reference' => 'WAL-' . uniqid()
        ]);
    }
}