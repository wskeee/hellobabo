<?php


namespace apiend\modules\v1\actions\order;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderGoodsScenePage;
use common\models\User;
use common\utils\PoseUtil;

class CheckPose extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['page_id', 'filepath'];

    public function run()
    {
        /* @var $user User */
        $user = \Yii::$app->user;
        $ogs_page_id = $this->getSecretParam('ogs_page_id');
        $filepath = $this->getSecretParam('filepath');

        /* @var $ogs_page OrderGoodsScenePage */
        $ogs_page = OrderGoodsScenePage::find($ogs_page_id);
        $source_page = $ogs_page->sourcePage;

        if ($source_page && $source_page->pose) {
            $tran = \Yii::$app->db->beginTransaction();
            // 计算得分
            $avg_score = PoseUtil::check($filepath, json_encode($source_page->pose->pose_data, true));

            // 更新用户表演积分
            $game_data = $user->gameData;
            $game_data->play_score += $avg_score;

            // 保存当前场景得分
            $ogs_page->user_data = json_encode(['play_score' => $avg_score]);
            try {
                $res = $game_data->save();
                $res1 = $ogs_page->save();
                if ($res && $res1) {
                    $tran->commit();
                    return new Response(Response::CODE_COMMON_OK, null, [
                        'ogs_page_id' => $ogs_page_id,
                        'play_score' => $avg_score,
                        'gameData' => $game_data,
                    ]);
                } else {
                    $tran->rollBack();
                    throw new \Exception('保存出错！');
                }
            } catch (\Exception $e) {
                $tran->rollBack();
                return new Response(Response::CODE_COMMON_SAVE_DB_FAIL);
            }
            //
        } else {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, ['param' => \Yii::t('app', 'Data')]);
        }
    }
}