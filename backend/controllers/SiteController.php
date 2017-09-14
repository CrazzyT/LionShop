<?php
namespace backend\controllers;

use backend\models\Admin;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * ACF 认证
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','reset-pwd','send-mail'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],       //认证用户
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(['index/index']);
//        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'signin';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 发送邮件
     */
    public function actionSendMail()
    {
        $this->layout = 'signin';

        $admin = new Admin(['scenario'=>'resetpwd']);
        if (Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            if ($admin->load($post) && $admin->validate())
            {
                //验证库中是否存在此email
                $adminOne = Admin::findOne(['email'=>$admin->email]);
                if ($adminOne)
                {
                    $timestamp = time();
                    $token = $this->createToken($timestamp,$admin->email);
                    //发送email
                    Yii::$app->mailer->compose('passwordreset',['timestamp'=>$timestamp,'email'=>$admin->email,'token'=>$token,'adminname'=>$admin->username])
                        ->setFrom('1278729699@qq.com')
                        ->setTo($admin->email)
                        ->setSubject('haha')
                        ->send();
                }
                else
                {
                    $admin->addError('email','此邮箱不存在');
                }
            }

        }
        return $this->render('reset-pwd',['admin'=>$admin]);
    }

    public function actionResetPwd()
    {
        echo '----';
    }

    protected function createToken($time,$email)
    {
        return md5(base64_encode(Yii::$app->request->userIP) . base64_encode($email) .base64_encode($time));
    }
}
