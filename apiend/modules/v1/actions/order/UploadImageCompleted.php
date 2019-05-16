<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;

/**
 * 上图完成
 */
class UploadImageCompleted extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        
        
        return new Response(Response::CODE_COMMON_OK, null, [
            
        ]);
    }

}
