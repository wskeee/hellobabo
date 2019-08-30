<?php

namespace apiend\modules\v1\actions\help;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Post;

/**
 * 获取问题
 *
 * @author wskeee
 */
class GetList extends BaseAction
{

    public function run()
    {
        $result = Post::find()
                ->select(['id', 'title'])
                ->where(['is_publish' => 1])
                ->orderBy('type_id')
                ->asArray()
                ->all();

        return new Response(Response::CODE_COMMON_OK, null, ['list' => $result]);
    }

}
