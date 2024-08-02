<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrFolder extends Model
{
    use HasFactory, GeneratesUuid, SoftDeletes;

    protected $fillable = [
        'name', 
        'uuid',
        'category',
        'parent_id',
        'modifier_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    const types = [
        'TG' => 'TECHNOLOGY GUIDE'
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
        return $this->hasMany(HrFile::class, 'folder_id');
    }

    public function parent()
    {
        return $this->belongsTo(HrFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(HrFolder::class, 'parent_id');
    }

    public function modifier()
    {
        return $this->belongsTo(Employee::class, 'modifier_id');
    }

    public function scopeOfCategories($query, string $type)
    {
        return $query->where('category', $type);
    }
}
