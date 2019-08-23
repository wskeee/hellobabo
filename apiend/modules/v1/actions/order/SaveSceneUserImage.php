<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderGoodsScenePage;
use Yii;

/**
 * 保存用户上传到场景的图片
 *
 * @author Administrator
 */
class SaveSceneUserImage extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['ogp_id', 'user_img_url'];

    public function run()
    {
        $ogp_id = $this->getSecretParam('ogp_id');
        $page = OrderGoodsScenePage::findOne(['id' => $ogp_id, 'is_del' => 0]);
        if ($page == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Scene')]);
        }
        $page->user_img_url = $this->getSecretParam('user_img_url');
        if ($page->save()) {
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $page->getErrorSummary(true));
        }
    }

}
