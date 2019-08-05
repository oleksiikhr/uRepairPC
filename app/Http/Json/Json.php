<?php

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
    private $_filePath;

    /**
     * @var string
     */
    private $_folder = 'global';

    /**
     * @var string
     */
    private $_disk = 'public';

    public function __construct($filePath, $folder, $disk)
    {
        $this->_filePath = $filePath;
        $this->_folder = $folder;
        $this->_disk = $disk;
    }

    /**
     * Get data from file or uses default.
     *
     * @return array
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
     * @param array $data - from $request->validate() or other data
     */
    public function transformDataAndRequestFiles(array &$data)
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
                            FileHelper::delete($json[$key], $this->_disk);
                        }

                        // Save new file if exists
                        if (\request()->hasFile($key)) {
                            $fileHelper = new FileHelper($data[$key]);
                            $data[$key] = $fileHelper->store($this->_folder, $this->_disk);
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
     * Save data to file.
     *
     * @param  array  $arr
     * @return void
     */
    public function mergeAndSaveToFile($arr)
    {
        $mergeData = array_merge($this->getDefaultData(), $arr);
        $json = json_encode($mergeData, JSON_PRETTY_PRINT);
        Storage::put($this->_filePath, $json);
    }

    /**
     * Get data from file.
     *
     * @return null|array
     */
    private function getFileData()
    {
        if (Storage::exists($this->_filePath)) {
            try {
                $data = Storage::get($this->_filePath);
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
