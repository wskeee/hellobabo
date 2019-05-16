<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;

/**
 * 初始绘本
 * 提交已选择的素材和场景
 */
class Init extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        
        
        return new Response(Response::CODE_COMMON_OK, null, [
            
        ]);
    }

}
