<?php

namespace Modules\ChattingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ConversationFile extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'conversation_id',
        'original_file_name',
        'stored_file_name',
        'file_type',
    ];

    protected static function newFactory()
    {
        return \Modules\ChattingModule\Database\factories\ConversationFileFactory::new();
    }

    protected $appends = ['file_size'];

    public function getFileSizeAttribute(): ?string
    {
        if ($this->attributes['stored_file_name']) {
            $path = 'public/conversation/' . $this->attributes['stored_file_name'];

            if (Storage::disk('local')->exists($path)) {
                $fileSizeBytes = Storage::disk('local')->size($path);
                return $this->formatSizeUnits($fileSizeBytes);
            } else {
                return null;
            }
        }

        return null;
    }

    private function formatSizeUnits($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }




}

