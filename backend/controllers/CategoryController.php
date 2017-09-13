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

    /**
     * 分类删除
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');

        $childCount = Category::find()->where('parent_id=:id',['id'=>$id])->count();
        if (!empty($childCount))
        {
            $this->error('该分类下有子类，不可删除！');
        }
        else
        {
            if(Category::findOne($id)->delete())
            {
                $this->success('删除成功！',['category/index']);
            }
            else
            {
                $this->error('删除失败！');
            }
        }
        $this->redirect(['category/index']);
    }

    public function actionIndex()
    {
        $categories = Category::level(Category::find()->asArray()->all());
        return $this->render('index',['categories'=>$categories]);
    }

    /**
     * 分类修改
     */
    public function actionUpdate($id)
    {
        $category = Category::findOne($id);

        if(Yii::$app->request->isPost)
        {
            if($category->load(Yii::$app->request->post()) && $category->validate())
            {
                if($res = $category->save())
                {
                    $this->success('修改成功.','category/index');
                }
                else
                {
                    $this->error('修改失败.');
                }
            }
            else
            {
                $this->error('数据不合法');
            }
        }

        // 下拉菜单
        $categories = Category::level(Category::find()->asArray()->all(),$id);
        $dropDownList = $category->dropDownList($categories);

        return $this->render('update',['category'=>$category,'dropDownList'=>$dropDownList]);
    }

}
