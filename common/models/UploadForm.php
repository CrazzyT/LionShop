<?php
/**
 * Created by PhpStorm.
 * User: CrazyT
 * Date: 2017/9/12
 * Time: 19:44
 */

namespace common\models;


use yii\base\Model;

class UploadForm extends Model
{
    public $imageFile;
    private $rootPath = 'uploads';
    private $fileInfo = array();
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $uploads = $this->createPath();
            $baseName = $this->createFileName();
            //移动临时文件
            $this->imageFile->saveAs($uploads.'/'. $baseName . '.' . $this->imageFile->extension);
            return true;
        }
        else
        {
            return false;
        }
    }

    //重命名文件名
    private function createFileName()
    {
        $str = '132456444489fdjvhkdhfkdhs';
        $randLetter = substr(str_shuffle($str), -5);
        return time().rand(10000,99999).$randLetter;
    }

    //创建目录
    private function createPath()
    {
        $imageFile = $this->rootPath.'/'.date('Y').'/'.date('m').'/'.date('d');
        if(!file_exists($imageFile))
        {
            if(!mkdir($imageFile,0777,true))
            {
                $this->error = '创建目录失败';
                return false;
            }
        }
        return $imageFile;
    }
}