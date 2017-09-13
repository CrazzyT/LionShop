<?php
/**
 * Created by PhpStorm.
 * User: CrazyT
 * Date: 2017/9/12
 * Time: 17:21
 */

namespace backend\controllers;

use Yii;
use common\models\UploadForm;
use yii\web\UploadedFile;

class AdminController extends IndexController
{
    public function actionInfo()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost)
        {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
//            if ($model->upload()) {
//                // 文件上传成功
//                return;
//            }
            $res = $model->upload();
            var_dump($res);
        }

        return $this->render('info',['model'=>$model]);
    }
}