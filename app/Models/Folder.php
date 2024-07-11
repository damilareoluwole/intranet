<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, GeneratesUuid, SoftDeletes;

    protected $fillable = [
        'name', 
        'uuid',
        'parent_id',
        'modifier_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function toArray()
    {
        return [
            'id'            => $this->uuid,
            'name'          => $this->name,
            'children'      => $this->children,
            'files'         => $this->files,
            'modifiedBy'    => $this->modifier->displayName,
            'modifiedOn'    => $this->updated_at->diffForHumans()
        ];
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function modifier()
    {
        return $this->belongsTo(Employee::class, 'modifier_id');
    }
}
