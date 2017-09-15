<?php
namespace common\helpers;

use Yii;
use Yii\helpers\Url;

class Tools
{
    static function success($msg='',$url=[],$skip=true,$wait=1)
    {
        $url = !empty($url) ? Url::toRoute($url) : '';
        Yii::$app->session->setFlash('alerts',['msg'=>$msg,'url'=>$url,'state'=>1,'wait'=>$wait,'skip'=>$skip]);
    }

    static function error($msg,$url=[],$skip=true,$wait=1)
    {
        $url = !empty($url) ? Url::toRoute($url) : '';
        Yii::$app->session->setFlash('alerts',['msg'=>$msg,'url'=>$url,'state'=>0,'wait'=>$wait,'skip'=>$skip]);
    }
}