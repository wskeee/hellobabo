<?php

namespace apiend\modules\v1\actions\activity;

use apiend\components\voterank\VoteService;
use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivityHand;

/**
 * 获取参赛数据
 *
 * @author Administrator
 */
class GetRankHand extends BaseAction
{

    public function run()
    {
        $uid = \Yii::$app->user->id;
        // 每一种情况，通过 hand_id 获取选手详情及排行数据
        $hid = $this->getSecretParam('hand_id');
        // 每二种情况，通过 aid和rank_num(排名) 获取选手详情及排行数据
        $aid = $this->getSecretParam('activity_id');
        $rank_num = $this->getSecretParam('rank_num');
        $data = [];
        
        if ($hid != null) {
            $model = VoteActivityHand::findOne(['id' => $hid]);
            $aid = $model->activity_id;
            $data = ['activity' => $model->activity->toDetail()];
        } else {
            // 获取指定hand 排行数据
            $rank = VoteService::getHandAllRanklist($aid, $rank_num, $rank_num + 1);
            if (count($rank) == 0) {
                return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => '数据']);
            } else {
                $hid = array_keys($rank)[0];
                $model = VoteActivityHand::findOne(['id' => $hid]);
            }
        }
        $data = array_merge($data,[
            'activity_id' => $aid,
            'hand_id' => $hid,
            'hand' => $model,
            'rank_data' => [
                'rank_num' => $rank_num,
                'is_vote' => VoteService::getUserIsVote($aid, $hid, $uid),
                'max_hand' => VoteService::getHandAllNum($aid),
                'all_rank' => intval(VoteService::getHandAllRank($aid, $hid)),
                'all_score' => intval(VoteService::getHandAllCount($aid, $hid)),
                'week_rank' => intval(VoteService::getHandWeekRank($aid, $hid)),
                'week_score' => intval(VoteService::getHandWeekCount($aid, $hid)),
            ]
        ]);
        
        
        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

}
