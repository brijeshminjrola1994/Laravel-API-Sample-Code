<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    public $timestamps = false;
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'user_id',
        'business_style',
        'postal_code',
        'address',
        'representative_phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
