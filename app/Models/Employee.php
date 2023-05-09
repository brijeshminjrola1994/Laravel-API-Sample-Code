<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'company_id',
        'name',
        'date_of_birth',
        'age',
        'blood_type',
        'postal_code',
        'address',
        'phone_number',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
