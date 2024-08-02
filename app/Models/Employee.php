<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use HasFactory, Notifiable;

    public function files()
    {
        return $this->hasMany(File::class, 'modifier_id', 'id');
    }

    public function hrfiles()
    {
        return $this->hasMany(HrFile::class, 'modifier_id', 'id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'modifier_id', 'id');
    }

    public function hrfolders()
    {
        return $this->hasMany(HrFolder::class, 'modifier_id', 'id');
    }

    public function informationRequests()
    {
        return $this->hasMany(ResearchInformationRequest::class, 'requester_id', 'id');
    }
}
