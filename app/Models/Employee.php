<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function files()
    {
        return $this->hasMany(File::class, 'modifier_id', 'id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'modifier_id', 'id');
    }

    public function informationRequests()
    {
        return $this->hasMany(ResearchInformationRequest::class, 'requester_id', 'id');
    }
}
