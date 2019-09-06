<?php

namespace apiend\controllers;

use common\models\goods\Goods;
use common\models\LoginForm;
use common\models\order\OrderGoods;
use common\models\User;
use common\utils\PosterUtils;
use jianyan\easywechat\Wechat;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
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
     * {@inheritdoc}
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
        $type = 2;
        $id = 11;
        //$rec_id = $this->getSecretParam('rec_id', Yii::$app->user->id);

        $savePath = Yii::$app->params['webuploader']['savePath'];
        /* @var $wechat Wechat  */
        /*
          $wechat = \Yii::$app->wechat;
          $response = $wechat->miniProgram->app_code->getUnlimit('1_5_16', ['page' => 'pages/goods/goods']);
          if ($response instanceof StreamResponse) {
          $filename = $response->saveAs('/web/app_code/', 'appcode.png');
          } */
        // 用户
        /* @var $user User */
        $user = User::find()->one();
        // 二维码路径
        $code_object = Yii::getAlias('@app/web/imgs/app_code.png');
        // 绘本封面或者用户封面
        $modelClass = $type == 1 ? Goods::class : OrderGoods::class;
        $model = $modelClass::findOne(['id' => $id]);

        $model_img = $type == 1 ? $model->cover_url : $model->user_cover_url;
        /* @var $goods_model Goods */
        $goods_model = $type == 1 ? $model : Goods::findOne(['id' => $model->goods_id]);
        $goods_poster = $goods_model->poster_url;


        $config = [
            // 用户昵称
            'text' => [
                [
                    'text' => $user->nickname,
                    'left' => 142,
                    'top' => 50,
                    'fontPath' => Yii::getAlias('@apiend/web/css/msyhbd.ttf'), //字体文件
                    'fontSize' => 22, //字号
                    'fontColor' => '255,124,45', //字体颜色
                    'angle' => 0,],
                [
                    'text' => "向你分享了《 $goods_model->goods_name 》",
                    'left' => 142,
                    'top' => 85,
                    'fontPath' => Yii::getAlias('@apiend/web/css/msyh.ttf'), //字体文件
                    'fontSize' => 18, //字号
                    'fontColor' => '255,116,4', //字体颜色
                    'angle' => 0,],
            ],
            'image' => [
                // 封面
                ['url' => $model_img,
                    'left' => 170,
                    'top' => 345,
                    'right' => 0,
                    'stream' => 0,
                    'bottom' => 0,
                    'width' => 473,
                    'height' => 625,
                    'opacity' => 100],
                // 二维码
                ['url' => "$code_object", //图片资源路径
                    'left' => 520,
                    'top' => 1110,
                    'stream' => 0, //图片资源是否是字符串图像流
                    'right' => 0,
                    'bottom' => 0,
                    'width' => 196,
                    'height' => 196,
                    'opacity' => 100],
                // 图像
                ['url' => $user->avatar,
                    'left' => 30,
                    'top' => 23,
                    'right' => 0,
                    'stream' => 0,
                    'bottom' => 0,
                    'width' => 96,
                    'height' => 96,
                    'opacity' => 100],
            ],
            'background' => $goods_poster,
        ];
        // 新建文件路径
        $filename = md5(time() . rand(100000, 999999)) . '.jpg';
        $filepath = Yii::getAlias('@apiend/web/upload/qrcode/' . $filename);
        // 创建海报
        PosterUtils::createPoster($config, $filepath);
        $oss_object = $savePath . $filename;

        exit;

        return $this->render('spec');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

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

}
