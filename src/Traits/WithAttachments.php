<?php

namespace Rollswan\CentralizedAttachment\Traits;

use Rollswan\CentralizedAttachment\Models\Attachment;
use Illuminate\Support\Facades\Storage;

trait WithAttachments
{
    /**
     * Store Attachment
     *
     * @param mixed $file
     * @param string $folderName
     * @param string $ownerModel
     * @param int $ownerModelID
     * @param string $generatedFileExtName
     * @param string $disk
     * @return \Rollswan\CentralizedAttachments\Models\Attachment
     */
    public function storeAttachment($file, $folderName, $ownerModel, $ownerModelID, $generatedFileExtName = null, $disk = 'local')
    {
        // Check if the attach file is for generated/created file by supplying $generatedFileExtName argument variable
        if (!$generatedFileExtName) {
            $filename = $file->getClientOriginalName();
            $storeAs = $ownerModelID . $file->getClientOriginalExtension();

            // Store file from input file upload
            $file->storeAs($folderName, $storeAs, $disk);
        } else {
            $filename = $ownerModelID;
            $storeAs = $filename . '.' . $generatedFileExtName;

            // Store generated/created file
            Storage::disk($disk)->put("/{$folderName}/{$storeAs}", $file);
        }

        // Create attachment record
        return Attachment::create([
            'filename' => $filename,
            'path' => "/{$folderName}/{$storeAs}",
            'owner_model' => $ownerModel,
            'owner_id' => $ownerModelID
        ]);
    }

    /**
     * Stream|Retrieve Attachment
     *
     * @param \Rollswan\CentralizedAttachment\Models\Attachment $attachment
     * @param string $disk
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function streamAttachment(Attachment $attachment, $disk)
    {
        return response()->file(Storage::disk($disk)->get($attachment->path));
    }

    /**
     * Delete Attachment
     *
     * @param \Rollswan\CentralizedAttachment\Models\Attachment $attachment
     * @param string $disk
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function deleteAttachment(Attachment $attachment, $disk)
    {
        return Storage::disk($disk)->delete($attachment->path);
    }
}
