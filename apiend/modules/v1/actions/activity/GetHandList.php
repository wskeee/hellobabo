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
 * 获取参赛数据列表
 *
 * @author Administrator
 */
class GetHandList extends BaseAction
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
        //关键字，搜索 设备名称、商家名称、代理名称
        $keyword = urldecode($this->getSecretParam('keyword'));
        //获取总数
        $get_total = $this->getSecretParam('get_total', 0);
        //指定id
        $ids = [];
        //排行信息
        $rank = null;

        if ($keyword == "") {
            $start = ($page - 1) * $limit;
            $rank = VoteService::getHandAllRanklist($activity_id, $start, $start + $limit);
            $ids = array_keys($rank);
        }

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

        $query->andFilterWhere(['ActivityHand.num' => $keyword]);
        $query->andFilterWhere(['ActivityHand.id' => $ids]);

        $hands = ArrayHelper::index($query->all(), 'id');
        $list = [];
        if ($keyword != "") {
            $ids = [reset($hands)['id']];
        }

        foreach ($ids as $id) {
            $hand = $hands[$id];
            $score = $rank ? $rank[$hand['id']] : VoteService::getHandWeekCount($activity_id, $hand['id']);
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
