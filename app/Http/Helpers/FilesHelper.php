<?php declare(strict_types=1);

namespace App\Http\Helpers;

use App\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class FilesHelper
{
    /**
     * @var UploadedFile[]
     */
    private $files;

    /**
     * @var File[]
     */
    private $filesUploaded;

    /**
     * @var array
     */
    private $errors;

    public function __construct($files)
    {
        $this->files = $files;
    }

    /**
     * @param {string} $storeFolder
     * @return void
     */
    public function upload($storeFolder)
    {
        $this->clear();

        foreach ($this->files as $file) {
            $fileHelper = new FileHelper($file);
            $fileModel = $fileHelper->fill();
            $uploadedUri = $fileHelper->store($storeFolder);

            if (! $uploadedUri) {
                $this->errors[$file->getClientOriginalName()] = [__('app.files.file_not_saved')];
                continue;
            }

            $fileModel->path = $uploadedUri;

            if (! $fileModel->save()) {
                $this->errors[$file->getClientOriginalName()] = [__('app.database.save_error')];
                FileHelper::delete($fileModel->path, $fileModel->disk);
                continue;
            }

            $this->filesUploaded[] = $fileModel;
        }
    }

    /**
     * @return Collection
     */
    public function getUploadedIds(): Collection
    {
        return collect($this->filesUploaded)->pluck('id');
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return File[]
     */
    public function getFilesUploaded()
    {
        return $this->filesUploaded;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    /**
     * Delete files from storage and records from File Model.
     *
     * @param  File[]  $files
     * @return bool - is success
     */
    public static function delete($files): bool
    {
        $deleteIds = [];

        foreach ($files as $file) {
            if (FileHelper::delete($file->path)) {
                $deleteIds[] = $file->id;
            }
        }

        $countDeleteIds = count($deleteIds);
        $isDestroyed = true;

        // Delete records from DB when file is deleted / not found
        if ($countDeleteIds) {
            $isDestroyed = File::destroy($deleteIds);
        }

        return count($files) === $countDeleteIds && $isDestroyed;
    }

    /**
     * @return void
     */
    private function clear()
    {
        $this->filesUploaded = [];
        $this->errors = [];
    }
}
