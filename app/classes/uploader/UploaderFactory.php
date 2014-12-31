<?php

namespace App\Classes\Uploader;

class UploaderFactory
{

    /**
     * 上传器工厂
     *
     * @return BaseUploader 生成Uploader
     * @author 
     **/
    public static function createUploader($type, $dir, $name=null, $remotes=null)
    {
        $clazz = ucfirst($type).'Uploader';
        if(class_exists($clazz)) {
            return new $clazz($type, $dir, $name, $remotes);
        } else {
            return new CommonUploader($type, $dir, $name, $remotes);
        }
    }
}