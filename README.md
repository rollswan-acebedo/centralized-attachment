# Centralized File Attachment

Provides centralized storing of file attachment.

## How to use?
1) Install `composer require rollswan/centralized-attachment`
2) Migrate `attachments` table by running `php artisan migrate`
3) Use `WithAttachments` trait in your controller

*Example:*

    namespace App\Http\Controllers;
    
    use Rollswan\CentralizedAttachment\Traits\WithAttachments;
    
    class ArticleController extends Controller
    {
        use WithAttachments;
    }

4) Use `storeAttachment()` method in controller, 

*Example:*

    $this->storeAttachment(
        $file, // This is for the attach file or generated file
        $folderName, // Attach file will store in the /storage/app/<folder-name> (for s3, bucket-name/<folder-name>)
        'App\Models\ModelName', // Owner Model
        $ownerModel, // Owner Model ID
        $generatedFileExtName, // Optional Param, To check whether the attach file is coming from file upload or generated file. Note: Set this file extension name argument if the attach file is generated/created file
        $disk // Optional Param, The default value is local disk. Simply change the filesystem disk if you wish to switch between the configured storage options
    );

    namespace App\Http\Controllers;
    
    use Rollswan\CentralizedAttachment\Models\Attachment;
    use Rollswan\CentralizedAttachment\Traits\WithAttachments;
    
    class ArticleController extends Controller
    {
        use WithAttachments;

        /**
         * Creates a new article.
         *
         * @param  CreateArticleRequest $request
         * @return \Illuminate\Http\RedirectResponse
         */
        public function create(CreateArticleRequest $request)
        {
            // Create the article
            $article = Article::create($request->except('_token'));

            // Process attachments, if any
            if (!empty($request->attachments)) {
                $this->storeArticleAttachments($article->id, $request->attachments);
            }

            session()->flash('success', "Article has been successfully created.");
            return back();
        }

        /**
         * Store article attachments.
         *
         * @param integer $articleId
         * @param \Illuminate\Http\UploadedFile $attachments
         */
        private function storeArticleAttachments($articleId, $attachments)
        {
            foreach ($request->attachments as $attachment) {
                $this->storeAttachment(
                    $attachment,
                    'articles',
                    'App\Models\Article',
                    $articleId
                );
            }
        }
    }    

5) Use `Attachment` model in controller

*Example:*

    namespace App\Http\Controllers;
    
    use Rollswan\CentralizedAttachment\Models\Attachment;
    use Rollswan\CentralizedAttachment\Traits\WithAttachments;
    
    class ArticleController extends Controller
    {
        use WithAttachments;

        /**
         * View attachment.
         *
         * @param  string $uuid
         * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function viewAttachment($uuid)
        {
            $attachment = Attachment::find($uuid);
            if (!$attachment) {
                abort(404);
            }

            return $this->streamAttachment($attachment, 'application/pdf');
        }
    }

6) Use `deleteAttachment()` method in controller

*Example:*

    namespace App\Http\Controllers;
    
    use Rollswan\CentralizedAttachment\Models\Attachment;
    use Rollswan\CentralizedAttachment\Traits\WithAttachments;
    
    class ArticleController extends Controller
    {
        /**
         * Delete article attachments.
         *
         * @param integer $articleId
         */
        private function deleteArticleAttachment($articleId)
        {
            // Get the article attachments
            $attachments = Attachment::where('owner_model', 'App\Models\Article')
                ->where('owner_id', $articleId)
                ->get();

            foreach ($attachments as $attachment) {
                $this->deleteAttachment($attachment);
            }
        }
    }    