<?php

namespace Rollswan\CentralizedAttachment\Models;

use Rollswan\Uuid\Traits\WithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use WithUuid, SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'attachment_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'path',
        'owner_model',
        'owner_id'
    ];

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $appends = [
        'filesize'
    ];

    /**
     * Gets the file size.
     *
     * @return string
     */
    public function getFilesizeAttribute()
    {
        $file = storage_path('app/' . $this->path);
        if (!is_file($file)) {
            return "0 bytes";
        } else {
            return number_format(filesize($file) / 1024, 2) . " KB";
        }
    }
}
