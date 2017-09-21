<?php
namespace common\helpers;

use yii;
use yii\helpers\Url;
use common\models\Goods;

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

    /**
     * 货号
     */
    static function createGoodsSn()
    {
        $snPrefix = Yii::$app->params['snPrefix'];
        $goodsSn =  $snPrefix . strtoupper(uniqid()) . rand(1000,9999);
        if(Goods::find()->select('goods_sn')->where('goods_sn=:sn',[':sn'=>$goodsSn])->count() > 0)
        {
            echo '-----------';
            return self::createGoodsSn();
        }
        return $goodsSn;
    }
}