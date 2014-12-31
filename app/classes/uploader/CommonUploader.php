<?php

namespace App\Classes\Uploader;

use Plupload;

class CommonUploader extends BaseUploader implements UploaderInterface
{
    /**
     * 处理文件
     */
    public function handleFile()
    {
        return $this->receive('file', function ($file) {
            return $this->handle($file);
        });
    }
}