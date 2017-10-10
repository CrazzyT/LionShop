<?php

namespace frontend\controllers;

use common\models\Category;
use yii;

class CategoryController extends \yii\web\Controller
{
    public $layout = 'navmain';

    public function actionIndex()
    {
        // 查询主导航
        $this->view->params['navigation'] = Category::getNavigation();

        //查询分类信息
        $cid = intval(Yii::$app->request->get('cid',0));
        $catInfo = Category::getCategoryInfo($cid);
        if (!$catInfo)
        {
            //Yii::$app->response->redirect(['index/index']);
        }

        $data = [

        ];

        return $this->render('index',$data);
    }

}
