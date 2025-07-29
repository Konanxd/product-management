<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'code',
        'is_used',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
