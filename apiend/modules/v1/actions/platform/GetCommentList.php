<?php


namespace apiend\modules\v1\actions\platform;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\CommentService;

class GetCommentList extends BaseAction
{
    protected $requiredParams = ['page'];

    public function run()
    {
        $page = $this->getSecretParam('page', 1);
        $limit = $this->getSecretParam('limit', 10);

        $result = CommentService::getList([], $page, $limit);
        return new Response(Response::CODE_COMMON_OK, null, $result);
    }
}