<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'amount',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => TransactionStatus::New,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => TransactionStatus::class,
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function (Transaction $transaction) {
            // TODO вынести в отдельный класс
            DB::beginTransaction();
            $account = $transaction->account()->lockForUpdate()->firstOrFail();
            $amount = $transaction->amount;
            if ($account->hasEnough($amount)) {
                $account->increment('balance', $amount);
                $transaction->status = TransactionStatus::Successful;
                $transaction->save();
            } else {
                $transaction->status = TransactionStatus::Failed;
                $transaction->save();
            }
            DB::commit();
        });

        // Если появится необходимость изменять транзакции, то нужно будет обработать и это событие
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
