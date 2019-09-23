<?php

namespace apiend\modules\v1\actions\activity;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivity;
use common\models\activity\VoteActivityHandApply;
use Yii;

/**
 * 保存申请参赛数据
 *
 * @author Administrator
 */
class SaveApplyHand extends BaseAction
{

    protected $requiredParams = ['activity_id', 'target_name', 'target_age', 'target_img'];

    public function run()
    {
        $activity_id = $this->getSecretParam('activity_id');
        $hand_id = $this->getSecretParam('id');

        if ($hand_id != null) {
            $model = VoteActivityHandApply::findOne(['id' => $hand_id]);
        } else {
            $model = new VoteActivityHandApply([
                'activity_id' => $activity_id,
                'target_user_id' => Yii::$app->user->id,
            ]);
        }

        if (!$model) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => '数据']);
        }
        
        if ($model->activity->status == VoteActivity::STATUS_END) {
            return new Response(Response::CODE_COMMON_FORBIDDEN, "活动已结束");;
        }

        $model->setAttributes([
            'target_name' => $this->getSecretParam('target_name'),
            'target_age' => $this->getSecretParam('target_age'),
            'target_img' => $this->getSecretParam('target_img'),
            'check_status' => 0,
        ]);

        if ($model->validate() && $model->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $model);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

}
