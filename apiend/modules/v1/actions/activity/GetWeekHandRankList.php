<?php

namespace apiend\modules\v1\actions\activity;

use apiend\components\voterank\VoteService;
use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivityHand;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 获取周排名数据
 *
 * @author Administrator
 */
class GetWeekHandRankList extends BaseAction
{

    protected $requiredParams = ['activity_id'];

    public function run()
    {
        $u_id = Yii::$app->user->id;

        $activity_id = $this->getSecretParam('activity_id');
        //指定要获取的页
        $page = $this->getSecretParam('page', 1);
        //每页返回多少条数据
        $limit = $this->getSecretParam('page_size', 50);
        //获取总数
        $get_total = $this->getSecretParam('get_total', 0);
        
        $start = ($page - 1) * $limit;
        //排行信息
        $rank = VoteService::getHandWeekRanklist($activity_id, null, null, $start, $start + $limit);
        //指定id
        $ids = array_keys($rank);

        $query = (new Query())
                ->select([
                    'ActivityHand.id',
                    'ActivityHand.num',
                    'ActivityHand.target_name',
                    'ActivityHand.target_age',
                    'ActivityHand.target_img',
                ])
                ->from(['ActivityHand' => VoteActivityHand::tableName()])
                ->where(['ActivityHand.activity_id' => $activity_id]);

        $query->andFilterWhere(['ActivityHand.id' => $ids]);

        $hands = ArrayHelper::index($query->all(), 'id');
        $list = [];

        foreach ($ids as $id) {
            $hand = $hands[$id];
            $score = $rank[$hand['id']];
            $hand['week_score'] = $score == null ? 0 : $score;
            $hand['done'] = VoteService::getUserIsVote($activity_id, $hand['id'], $u_id);
            $list[] = $hand;
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'total' => $get_total ? VoteService::getHandAllNum($activity_id) : 0,
            'page' => $page,
            'list' => $list,
        ]);
    }

}
