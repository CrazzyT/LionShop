<?php

namespace backend\controllers;

use common\helpers\Tools;
use common\models\Brand;
use common\models\Category;
use common\models\Goods;
use common\models\GoodsGallery;
use common\models\UploadForm;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $noCsrfActions = ['gallery','edit-img'];
        if(in_array($action->id,$noCsrfActions))
        {
            $action->controller->enableCsrfValidation = false;
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionCreate()
    {
        $model = new Goods();
        $upload = new UploadForm();
        if(Yii::$app->request->isPost)
        {
            if($model->createGoods())
            {
                Tools::success('商品添加成功',['goods/index']);
            }
            else
            {
                Tools::error('商品添加失败');
            }
        }
        // 下拉菜单
        $catList = (new Category())->dropDownList();
        $brandList = (new Brand())->dropDownList();
        return $this->render('create',['model'=>$model,'upload'=>$upload,'catList'=>$catList,'brandList'=>$brandList]);
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex($cid = '', $bid = '', $property = '', $sale = null, $name = null)
    {
        $map = ['cid' => $cid, 'bid' => $bid, 'property' => $property, 'name' => $name,'sale'=>$sale]; //搜索条件

        $catList = (new Category)->dropDownList();
        $brandList = (new Brand())->dropDownList();

        $query = $this->_search($map,Goods::find());

        $page = new Pagination(['defaultPageSize' => yii::$app->params['pageSize'], 'totalCount' => $query->count()]);

        $goodsList = $query->offset($page->offset)
            ->limit($page->limit)
            ->all();

        return $this->render('index',['map'=>$map,'catList'=>$catList,'brandList'=>$brandList,'page'=>$page,'goodsList'=>$goodsList]);
    }

    /**
     * 处理搜索条件
     */
    private function _search($map,$query)
    {
        $query->where('is_delete=:isdel',[':isdel'=>0]);
        // 处理分类搜索条件
        if(!empty($map['cid']))
        {
            $childs = Category::levels(Category::find()->select('cat_id,parent_id,cat_name')->asArray()->all(),'',$map['cid']);
            $cids = ArrayHelper::getColumn($childs,'cat_id');
            array_push($cids,$map['cid']);
            $query->andWhere(['in','cat_id',$cids]);
        }
        // 处理品牌搜索条件
        if(!empty($map['bid']))
        {
            $query->andWhere('brand_id=:bid',[':bid'=>$map['bid']]);
        }
        // 处理其他搜索条件
        if(!empty($map['property']))
        {
            $query->andWhere([$map['property']=>1]);
        }
        if(is_numeric($map['sale']))
        {
            $query->andWhere(['is_on_sale'=>$map['sale']]);
        }
        if(isset($map['name']) && !empty($map['name']))
        {
            $query->andWhere(['like','goods_name',$map['name']]);
        }
        return $query;
    }


    public function actionUpdate()
    {
        return $this->render('update');
    }

    /**
     * 上传图片至七牛云
     */
    public function actionGallery($gid='',$gname='')
    {
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax)
        {
            // 上传至七牛云
            $upForm = new UploadForm();
            $upForm->imageFile = UploadedFile::getInstance($upForm,'imageFile');
            $result = $upForm->uploadToQiNiu();
            if($result['code'] == 0)
            {
                // 入库
                $gid = Yii::$app->request->post('gid');
                $data = ['goods_id'=>$gid,'original_img'=>$result['data']['src']];
                if(!(new GoodsGallery())->createGallery($data))
                {
                    $result['code'] = 200;
                    $result['msg'] = '图片入库失败.';
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            // 响应 JSON
            return $result;
        }
        // 该商品下的图片
        $galleries = (new GoodsGallery())->getGalleries($gid);
        return $this->render('gallery',['gname'=>$gname,'gid'=>$gid,'galleries'=>$galleries]);
    }

    /**
     * 删除商品相册
     */
    public function actionDeleteImg($key)
    {
        $result = ['code'=>0,'msg'=>'删除成功.','data'=>[]];
        $error = (new UploadForm)->deleteFile($key);
        if($error == null)
        {
            // 删除成功
            $goodsGellery = GoodsGallery::find()->where(['original_img'=>$key])->one();
            if(!is_null($goodsGellery) && !$goodsGellery->delete())
            {
                $result['code'] = 300;
                $result['msg'] = '从数据库删除失败.';
            }
        }
        else
        {
            $result['code'] = $error->code();
            $result['msg'] = $error->message();
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * 修改商品相片描述
     *
     * @return int
     */
    public function actionEditImg()
    {
        $img_desc = Yii::$app->request->post('img_desc');
        $original_img = Yii::$app->request->post('original_img');
        $result = GoodsGallery::updateAll(['img_desc'=>$img_desc],'original_img=:oimg',[':oimg'=>$original_img]);
        return $result;
    }
}
