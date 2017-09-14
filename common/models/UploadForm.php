<?php
/**
 * Created by PhpStorm.
 * User: CrazyT
 * Date: 2017/9/12
 * Time: 19:44
 */

namespace common\models;


use yii\base\Model;
use yii\base\Exception;
use yii\base\ErrorException;

class UploadForm extends Model
{
    protected $rootPath = './uploads/';    //上传的根目录
    protected $filePath;                   //上传后的目录
    public $file;                       //上传后的文件位置
    public $imageFile;                     //文件对象


    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * 文件上传
     */
    public function upload()
    {
        //上传文件则处理
        if (is_null($this->imageFile))
        {
            return null;
        }
        if ($this->validate())
        {
            //新建目录
            $this->createPath();

            //新建文件名
            $fileName = uniqid().rand(10000,99999);

            //移动临时文件
            $file = $this->filePath.$fileName.'.'.$this->imageFile->extension;

            $this->file = $file;
            return $this->imageFile->saveAs($file);
        }
        else
        {
            return false;
        }
    }

    //创建目录
    private function createPath()
    {
        $path = $this->rootPath.date('Y').'/'.date('m').'/'.date('d').'/';
        try{
            if(!file_exists($path))
            {
                if(!mkdir($path,0777,true))
                {
                    throw new ErrorException('创建目录失败');
                }
            }
            $this->filePath = $path;
            return true;
        }
        catch (Exception $e)
        {
            exit($e->getMessage());
        }

    }
}