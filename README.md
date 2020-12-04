

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
        True // Optional Param, Default Value is False: set to true if the attach file is generated file
    );
