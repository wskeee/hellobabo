<?php

namespace apiend\modules\v1\controllers;

use apiend\modules\v1\actions\wx_pay\PayCb;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * 套餐
 *
 * @author Administrator
 */
class WxPayController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'pay-cb' => ['post'],
            ]
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'pay-cb' => ['class' => PayCb::class],
        ];
    }

}
