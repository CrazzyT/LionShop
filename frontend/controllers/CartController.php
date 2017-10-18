<?php

namespace frontend\controllers;

use common\models\Goods;
use frontend\models\Cart;
use yii;

class CartController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //判断有没有登录
        $id= Yii::$app->user->identity->id;
        $cartLists = Cart::getCartList($id);
        $price = 0;
        foreach ($cartLists as $v)
        {
            $price +=($v['goods_price']*$v['buy_number']);
        }
        //查询下面的推荐和热销和新品
        $this->view->params['best'] = Goods::getRecommendGoods('is_best','1','3');
        $this->view->params['hot'] = Goods::getRecommendGoods('is_hot','1','3');
        $this->view->params['new'] = Goods::getRecommendGoods('is_new','1','3');
        $data = [
            'cartLists'   => $cartLists,
            'price'       => $price
        ];
        return $this->render('index',$data);
    }


}
