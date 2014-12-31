<?php

namespace App\Classes\Uploader;

interface UploaderInterface
{
    /**
     * 供使用者调用
     */
    public function run();

    /**
     * 处理文件
     * @return array  file info数组
     */
    public function handleFile();

    /**
     * 拷贝文件到云端备份
     * @return array 云端返回数据
     */
    public function copyTo();

    /**
     * 组合文件info和云端返回信息，形成最后的返回值
     * @return array 最后返回数组的数据
     */
    public function combine($fileInfo, $remoteInfo);
}