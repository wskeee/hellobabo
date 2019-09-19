<?php

namespace frontend\modules\activity\controllers;

use common\models\activity\VoteActivity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `activity` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionView($id)
    {
        $model = VoteActivity::findOne(['id' => $id]);

        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['model' => $model]);
    }

}
