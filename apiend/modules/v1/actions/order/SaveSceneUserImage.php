<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderGoodsScenePage;
use common\models\User;
use common\utils\PoseUtil;
use Yii;
use yii\db\Exception;

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
        $tran = \Yii::$app->db->beginTransaction();
        try {
            $res = $page->save();
            $result = $this->checkPose($page, $page->user_img_url);
            if ($res && $result) {
                $tran->commit();
                return new Response(Response::CODE_COMMON_OK, null, $result);
            } else {
                $tran->rollBack();
                throw new \Exception("保存出错！ res:{$res} result:{$result}");
            }

        } catch (\Exception $e) {
            $tran->rollBack();
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, $e->getMessage());
        }
    }

    /**
     * 检查 pose
     * @param OrderGoodsScenePage $page
     * @param string $filepath
     * @return array|bool
     * @throws \Exception
     */
    private function checkPose($page, $filepath)
    {
        /**
         * 当得分在90~100分之间时，出现文字：动作完美，+10个演绎分。
         * 当得分在80~90分之间时，出现文字：动作不错，+5个演绎分。
         * 当得分在80分以下时，出现文字：动作不行喔，请重新拍摄上传。
         */
        $marks = [
            ['avg_score' => 0,'play_score' => 0,],
            ['avg_score' => 80,'play_score' => 5,],
            ['avg_score' => 90,'play_score' => 10,]
        ];

        $filepath .= '?x-oss-process=image/resize,m_lfit,h_720,w_720';
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        $source_page = $page->sourcePage;

        if ($source_page && $source_page->pose && $source_page->pose->pose_data) {
            // 计算得分
            $check_result = PoseUtil::check($filepath, json_decode($source_page->pose->required_data, true));
            $avg_score = $check_result['avg_score'];
            $play_score = 0;

            for ($i = count($marks)-1; $i >= 0; $i--) {
                if ($avg_score >= $marks[$i]['avg_score']) {
                    $play_score = $marks[$i]['play_score'];
                    break;
                }
            }

            // 更新用户表演积分
            $game_data = $user->gameData;
            $game_data->play_score = $game_data->play_score + $play_score;

            // 保存当前场景得分
            $page->user_data = json_encode(['play_score' => $avg_score]);
            $game_data->save();
            $page->save();
            return [
                'ogp_id' => $page->id,
                'play_score' => $avg_score,
                'gameData' => $game_data,
            ];
        } else {
            return [
                'ogp_id' => $page->id,
                'play_score' => 0,
                'gameData' => $user->gameData
            ];
        }
    }

}
