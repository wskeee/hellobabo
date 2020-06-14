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

        // 数据限制检查
        $has_num = CommentService::getCommentNumByDate($user->id, strtotime('today 00:00:00'), strtotime('today 23:59:59'));
        if ($has_num >= 5) {
            return new Response(Response::CODE_COMMON_FORBIDDEN, '超出每天留言最大数量限制！');
        }
        $comment = CommentService::add($title, $content, $user->id, $is_hide);
        if ($comment) {
            return new Response(Response::CODE_COMMON_OK, null, $comment);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL);
        }
    }
}