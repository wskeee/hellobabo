<?php

namespace apiend\modules\v1\actions\goods;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsMaterialValue;

/**
 * 获取素材列表
 *
 * @author Administrator
 */
class GetMaterialList extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['material_id'];

    public function run()
    {
        $list = GoodsMaterialValue::find()
                        ->where([
                            'material_id' => $this->getSecretParam('material_id'),
                            'is_del' => 0,
                        ])->all();

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }

}
