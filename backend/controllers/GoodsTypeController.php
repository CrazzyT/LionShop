<?php

namespace backend\controllers;

use common\helpers\Tools;
use yii;
use backend\models\GoodsType;

class GoodsTypeController extends \yii\web\Controller
{
    public function actionCreate()
    {
        $model = new GoodsType();
        if (Yii::$app->request->isPost)
        {
            if ($model->createGoodsType())
            {
                Tools::success('类型添加成功',['goods-type/index']);
            }
            else
            {
                Tools::error('类型添加失败');
            }
        }
        return $this->render('create',['model'=>$model]);
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex()
    {
        $query = GoodsType::find();
        $page = new yii\data\Pagination(['totalCount'=>$query->count(),'defaultPageSize'=>Yii::$app->params['pageSize']]);
        $typeList = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['typeList'=>$typeList,'page'=>$page]);
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

}
