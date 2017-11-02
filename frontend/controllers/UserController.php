<?php

namespace frontend\controllers;
use common\models\OrderInfo;
use frontend\models\Cart;
use Yii;
class UserController extends \yii\web\Controller
{
    public $userId;
    public function init()
    {
        // 验证是否登录
        if (Yii::$app->user->isGuest) {
            return $this->goBack();
        }
        $this->userId = Yii::$app->user->getId();
    }
    public function actionIndex()
    {
        // 查询购物车商品
        echo 'default page';
//        $cart = Cart::getCartList();
//        return $this->render('index', ['cart' => $cart]);
    }
    public function actionMyOrder()
    {
        $orderlist = OrderInfo::getMyOrder($this->userId);
        return $this->render('my-order',['orderList'=>$orderlist]);
    }
}