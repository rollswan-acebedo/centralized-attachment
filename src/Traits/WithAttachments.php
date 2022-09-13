<?php

namespace Rollswan\CentralizedAttachment\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Rollswan\CentralizedAttachment\Models\Attachment;

trait WithAttachments
{
    /**
     * Store Attachment
     *
     * @param  mixed $file
     * @param  string $folderName
     * @param  string $ownerModel
     * @param  int $ownerModelID
     * @param  string $generatedFileExtName
     * @param  string $disk
     * @return \Rollswan\CentralizedAttachments\Models\Attachment
     */
    public function storeAttachment(
        $file,
        $folderName,
        $ownerModel,
        $ownerModelID,
        $generatedFileExtName = null,
        $disk = 'local'
    ) {
        $uuid = (string)Str::uuid();

        // Check if the attach file is for generated/created file by supplying $generatedFileExtName argument variable
        if (!$generatedFileExtName) {
            $filename = $file->getClientOriginalName();
            $storeAs = $uuid . '.' . $file->getClientOriginalExtension();

            // Store file from input file upload
            $file->storeAs($folderName, $storeAs, $disk);
        } else {
            $filename = $uuid;
            $storeAs = $filename . '.' . $generatedFileExtName;

            // Store generated/created file
            Storage::disk($disk)->put("/{$folderName}/{$storeAs}", $file);
        }

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
     * @param  \Rollswan\CentralizedAttachment\Models\Attachment $attachment
     * @param  string $mimetype
     * @param  string $disk
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function streamAttachment(Attachment $attachment, $mimetype, $disk = 'local')
    {
        return response(Storage::disk($disk)->get($attachment->path), 200)
            ->header('Content-Type', $mimetype)
            ->header('Content-Disposition', 'inline');
    }

    /**
     * Delete Attachment
     *
     * @param \Rollswan\CentralizedAttachment\Models\Attachment $attachment
     * @param string $disk
     */
    public function deleteAttachment(Attachment $attachment, $disk = 'local')
    {
        Storage::disk($disk)->delete($attachment->path);
        return $attachment->delete();
    }
}
