<?php

namespace frontend\controllers;

use common\models\Category;

class OrderController extends \yii\web\Controller
{
    public $layout = 'navmain';

    public function actionCheckout()
    {

        // 查询全部商品分类
        $navigation = Category::getNavigation();
        $this->view->params['navigation'] = $navigation;

        $data = [
            'navigation'=>$navigation,
        ];

        return $this->render('checkout',$data);
    }

}
