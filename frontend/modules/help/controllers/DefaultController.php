<?php

namespace frontend\modules\help\controllers;

use common\models\platform\Post;
use common\models\platform\searchs\PostSearch;
use yii\web\Controller;

/**
 * Default controller for the `help` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $posts = PostSearch::find()->where(['is_publish' => 1])->all();
        return $this->render('index', ['posts' => $posts]);
    }

    public function actionPost($id)
    {
        $post = Post::findOne(['id' => $id]);
        return $this->render('post', ['post' => $post]);
    }

}
