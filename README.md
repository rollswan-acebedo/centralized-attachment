# Centralized File Attachment

Provides centralized storing of file attachment.

## How to use?
1) Install `composer require rollswan/centralized-attachment`
2) Migrate `attachments` table by running `php artisan migrate`
3) Use `WithAttachments` trait in your controller

*Example:*

    namespace App\Http\Controllers;
    
    use Rollswan\CentralizedAttachment\Traits\WithAttachments;
    
    class PostController extends Controller
    {
        use WithAttachments;
    }


4) Use `storeAttachment()` in controller, 

*Example:*

    $this->storeAttachment(
        $file, // This is for the attach file or generated file
        'folder-name', // Attach file will store in the /storage/app/<folder-name> (for s3, bucket-name/<folder-name>)
        'App\Models\ModelName', // Owner Model
        $model->id, // Owner Model ID
        'png', // Optional Param, To check whether the attach file is coming from file upload or generated file. Note: Set this file extension name argument if the attach file is generated/created file
        's3' // Optional Param, The default value is local disk. Simply change the filesystem disk if you wish to switch between the configured storage options
    );

5) Use `Attachment` model in your controller

*Example:*

    namespace App\Http\Controllers;
    
    use Rollswan\CentralizedAttachment\Models\Attachment;
    use Rollswan\CentralizedAttachment\Traits\WithAttachments;
    
    class PostController extends Controller
    {
        use WithAttachments;

        /**
         * View file attachment.
         *
         * @param $uuid
         * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function viewFileAttachment($uuid)
        {
            $attachment = Attachment::find($uuid);
            if (!$attachment) {
                abort(404);
            }

            return $this->streamAttachment($attachment, 'application/pdf');
        }
    }