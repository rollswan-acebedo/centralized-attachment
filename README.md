# Centralized File Attachment

Provides centralized storing of file attachment.

## How to use?
1) Install `composer require rollswan/centralized-attachment`
2) use `WithAttachments` trait in your Controller

*Example:*

    namespace App\Http\Controllers;
    
    use Rollswan\CentralizedAttachment\Traits\WithAttachments;
    
    class PostController extends Controller
    {
        use WithAttachments;
    }


3) use `storeAttachment()` in Controller, 

*Example:*

    $this->storeAttachment(
        $file, // This is for the attach file or generated file
        'folder-name', // Attach file will store in the /storage/app/<folder-name>
        'App\Models\ModelName', // Owner Model
        $model->id, // Owner Model ID
        'png', // Optional Param, To check whether the attach file is coming from file upload or generated file. Note: Set this file extension name argument if the attach file is generated/created file
        's3' // Optional Param, The default value is local disk. Simply change the filesystem disk if you wish to switch between the configured storage options
    );
