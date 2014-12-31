<?php

namespace App\Classes\Uploader;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Plupload;

abstract class BaseUploader implements UploaderInterface
{
    protected $_type;
    protected $_dir;
    protected $_name;
    protected $_remotes;

    /**
     * 构造方法
     *
     * @param $type 文件类型
     * @param $dir 想要保存到的目录
     * @param $name 想要保存的文件名，如果不填则保留原来的文件名
     * @param $remotes 备份到云端的配置，默认不备份
     *
     * @author 
     **/
    public function __construct($type, $dir, $name=null, $remotes=null)
    {
        $this->_type = $type;
        $this->_dir = $dir;
        $this->_name = $name;
        $this->_remotes = $remotes;
    }

    /**
     * 供使用者调用
     */
    public function run()
    {
        $fileInfo = $this->handleFile($this->_dir, $this->_name);

        $remoteInfo = [];
        if(is_array($this->_remotes)) {
            foreach($this->_remotes as $remote) {
                $remoteInfo[] = $this->copyTo($config);
            }
        }
        return $this->combine($fileInfo, $remoteInfo);
    }

    /**
     * 处理文件
     */
    abstract public function handleFile();

    /**
     * 拷贝文件到云端备份
     */
    public function copyTo()
    {
        // TDO
    }

    /**
     * 组合文件info和云端返回信息，形成最后的返回值
     */
    public function combine($fileInfo, $remoteInfo)
    {
        // TDO
        return $fileInfo;
    }

    /**
     * hack plupload，只取其返回数组的result部分，此方法提供给子类调用（也可以不使用）
     *
     * @param string $name Plupload使用的name参数
     * @param Closure $handler 回调函数
     *
     * @return array 文件信息 file info
     * @author 
     **/
    protected function receive($name, \Closure $handler)
    {
        $result = Plupload::receive($name, $handler);

        return $result['result'];
    }

    /**
     * 返回基本的文件信息，此方法写在父类里，方便子类使用或者重写（也可以不使用）
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file UploadedFile对象
     *
     * @return array 文件信息 file info
     * @author 
     **/
    protected function handle($file)
    {
        try {
            if(empty($this->_name)) {
                $this->_name = $file->getClientOriginalName();
            }
            if(substr($this->_dir, -1) != '/') {
                $this->_dir = $this->_dir.'/';
            }
            $file->move($this->_dir, $this->_name);

            $verb = $file->getClientOriginalExtension();
            $size = $file->getClientSize();
            $path = $this->_dir.$this->_name;
            $md5 = md5_file($path);

            $data = compact('size', 'verb', 'dir', 'name', 'path', 'md5');

            try {
                $data['mime'] = $file->getClientMimeType();
            }catch(ExtensionGuesser $e) {
            }

            return ['success'=>true, 'data'=>$data];
        } catch(FileException $e) {
            return ['success'=>false, 'msg'=>'文件错误'];
        } catch(FileNotFoundException $e) {
            return ['success'=>false, 'msg'=>'找不到文件'];
        }
    }
}