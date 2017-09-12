<?php

namespace backend\controllers;
use common\models\Category;
use Yii;

class CategoryController extends IndexController
{
    public function actionCreate()
    {
        $category = (new Category())->loadDefaultValues();
        if (Yii::$app->request->isPost)
        {
           if ($category->load(Yii::$app->request->post()) && $category->validate())
           {
                if ($category->save())
                {
                    $this->success('分类添加成功','category/index');
                }
                else
                {
                    $this->error('分类添加失败');
                }
           }
        }
        //下拉数据
        $categories = Category::level(Category::find()->asArray()->all());
        $dropDownList = $category->dropDownList($categories);

        return $this->render('create',['category'=>$category,'dropDownList'=>$dropDownList]);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->get('id',0);
        $category = Category::findOne($id);
        if($category->delete())
        {
            $this->redirect(['category/index']);
        }
        else
        {
            die('del Fail');
        }
    }

    public function actionIndex()
    {
        $categories = Category::level(Category::find()->asArray()->all());
        return $this->render('index',['categories'=>$categories]);
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

}
