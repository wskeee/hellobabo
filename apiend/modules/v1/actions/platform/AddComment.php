<?php


namespace apiend\modules\v1\actions\platform;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\CommentService;

class AddComment extends BaseAction
{
    protected $requiredParams = ['content'];

    public function run()
    {
        $user = \Yii::$app->user->identity;
        $title = $this->getSecretParam('title', '');
        $content = $this->getSecretParam('content', '');
        $is_hide = $this->getSecretParam('is_hide', 0);

        $comment = CommentService::add($title, $content, $user->id, $is_hide);
        if ($comment) {
            return new Response(Response::CODE_COMMON_OK, null, $comment);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL);
        }
    }
}