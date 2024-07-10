<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'name', 
        'uuid',
        'path',
        'folder_id',
        'modifier_id',
        'type',
        'size',
        'file_name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function toArray()
    {
        return [
            'id'            => $this->uuid,
            'name'          => $this->file_name,
            'type'          => $this->type,
            'path'          => config('app.file_url').$this->path,
            'size'          => formatFileSize($this->size),
            'modifiedBy'    => $this->modifier->displayName,
            'modifiedOn'    => $this->updated_at->diffForHumans()
        ];
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function modifier()
    {
        return $this->belongsTo(Employee::class, 'modifier_id');
    }
}
