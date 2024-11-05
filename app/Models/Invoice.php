<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'invoice_number',
        'amount',
        'due_date',
        'status',
        'recurrence',
        'next_invoice_date'
    ];

    protected $dates = ['due_date', 'next_invoice_date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = date('Y');

        do {
            $lastInvoice = self::whereYear('created_at', $year)->latest()->first();

            $nextNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -5) + 1 : 1;

            $formattedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $invoiceNumber = $prefix . $year . $formattedNumber;

        } while (self::where('invoice_number', $invoiceNumber)->exists()); // Ensure uniqueness

        return $invoiceNumber;
    }
}
