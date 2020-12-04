<?php

namespace Rollswan\CentralizedAttachment\Traits;

use Rollswan\CentralizedAttachment\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait WithAttachments
{
    /**
     * Store Attachment
     *
     * @param mixed $file
     * @param string $type
     * @param string $model
     * @param int $modelOwner
     * @param bool $isGeneratedFile
     * @return \Rollswan\CentralizedAttachments\Models\Attachment
     */
    public function storeAttachment($file, $type, $model = null, $modelOwner = null, $isGeneratedFile = false)
    {
        // Prepare the data
        $uuid = (string)Str::uuid();

        // Check if the attach file is generated file
        if (!$isGeneratedFile) {
            $filename = $file->getClientOriginalName();
            $storeAs = $uuid . $file->getClientOriginalExtension();
            $file->storeAs($type, $storeAs);
        } else {
            $filename = $uuid;
            $storeAs = "/{$type}/". $uuid . '.png';
            Storage::disk('local')->put($storeAs, $file);
        }

        // Create attachment record
        return Attachment::create([
            'filename' => $filename,
            'path' => "{$type}/{$storeAs}",
            'owner_model' => $model,
            'owner_uuid' => $modelOwner
        ]);
    }
}
