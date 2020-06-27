<?php declare(strict_types=1);

namespace App\Http\Json;

use App\Http\Helpers\FileHelper;
use Illuminate\Support\Facades\Storage;

abstract class Json implements IJson
{
    /**
     * @var bool
     */
    public $isFromFile = false;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $folder = 'global';

    /**
     * @var string
     */
    private $disk = 'public';

    public function __construct($filePath, $folder, $disk)
    {
        $this->filePath = $filePath;
        $this->folder = $folder;
        $this->disk = $disk;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $json = $this->getFileData();

        if ($json) {
            $this->isFromFile = true;

            return $json;
        }

        return $this->getDefaultData();
    }

    /**
     * @param  array  $data - from $request->validate() or other data
     * @return void
     */
    public function transformDataAndRequestFiles(array &$data): void
    {
        $attributes = $this->getAttributes();
        $json = $this->getData();

        foreach ($attributes as $key => $type) {
            if (array_key_exists($key, $data)) {
                switch ($type) {
                    case 'bool': {
                        $data[$key] = (bool) $data[$key];
                        break;
                    }
                    case 'file': {
                        // Delete old file
                        if (array_key_exists($key, $json)) {
                            FileHelper::delete($json[$key], $this->disk);
                        }

                        // Save new file if exists
                        if (\request()->hasFile($key)) {
                            $fileHelper = new FileHelper($data[$key]);
                            $data[$key] = $fileHelper->store($this->folder, $this->disk);
                        } else {
                            $data[$key] = null;
                        }
                        break;
                    }
                    default: {
                        $data[$key] = $data[$key] ?? '';
                    }
                }
            }
        }

        $data = array_merge($json, $data);
    }

    /**
     * @inheritDoc
     */
    public function mergeAndSaveToFile($arr): void
    {
        $mergeData = array_merge($this->getDefaultData(), $arr);
        $json = json_encode($mergeData, JSON_PRETTY_PRINT);
        Storage::put($this->filePath, $json);
    }

    /**
     * Get data from file
     *
     * @return null|array
     */
    private function getFileData()
    {
        if (Storage::exists($this->filePath)) {
            try {
                $data = Storage::get($this->filePath);
                $json = json_decode($data);

                if ($json === null || json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Json data is incorrect');
                }

                return (array) $json;
            } catch (\Exception $e) {
                return;
            }
        }
    }
}
