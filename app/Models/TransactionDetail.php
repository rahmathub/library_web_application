<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'book_id',
        'qty',
    ];

    /**
     * Get the transaction associated with the transaction detail.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Get the book associated with the transaction detail.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
