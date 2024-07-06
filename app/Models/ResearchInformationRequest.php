<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchInformationRequest extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'uuid', 
        'first_name',
        'last_name',
        'group',
        'email',
        'information_required',
        'justification',
        'comment',
        'requester_id',
        'admin_id',
        'admin_at',
        'red_comment',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function toArray() {
        return [
            'uuid'                  => $this->uuid,
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'group'                 => $this->group,
            'email'                 => $this->email,
            'information_required'  => $this->information_required,
            'justification'         => $this->justification,
            'comment'               => $this->comment,
            'red_comment'           => $this->red_comment,
            'status'                => $this->status,
            'requester'             => $this->requester->displayName,
            'date_requested'        => $this->created_at->diffForHumans(),
            'admin'                 => $this->admin_id ? $this->admin->displayName : null,
            'admin_at'              => $this->admin_at ? Carbon::parse($this->admin_at)->diffForHumans() : null
        ];
    }

    public function requester() {
        return $this->belongsTo(Employee::class, 'requester_id');
    }

    public function admin() {
        return $this->belongsTo(Employee::class, 'admin_id');
    }
}
