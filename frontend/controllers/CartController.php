<?php

namespace frontend\controllers;

use frontend\models\Cart;

class CartController extends \yii\web\Controller
{
    public function actionIndex($uid)
    {
        return $this->render('index');
    }

}
