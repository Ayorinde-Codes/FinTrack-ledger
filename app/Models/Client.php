<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'industry',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function clientIps()
    {
        return $this->hasMany(ClientIp::class);
    }

    public function clientKeys()
    {
        return $this->hasOne(ClientKey::class);
    }

    public function expenses()
    {
        return $this->hasOne(Expense::class);
    }
}
