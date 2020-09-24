<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\activity\AddHandReady;
use apiend\modules\v1\actions\activity\CheckUserJoin;
use apiend\modules\v1\actions\activity\GetActivityByCode;
use apiend\modules\v1\actions\activity\GetDetail;
use apiend\modules\v1\actions\activity\GetHandList;
use apiend\modules\v1\actions\activity\GetNew;
use apiend\modules\v1\actions\activity\GetRankHand;
use apiend\modules\v1\actions\activity\GetWeekHandRankList;
use apiend\modules\v1\actions\activity\HandVote;
use apiend\modules\v1\actions\activity\JoinWelcomeActivity;
use apiend\modules\v1\actions\activity\SaveApplyHand;

/**
 * 活动
 *
 * @author Administrator
 */
class ActivityController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'get-new',
            'get-detail',
            'check-user-join',
            'get-activity-by-code',
        ];
        $behaviors['verbs']['actions'] = [
            'get-new' => ['get'],
            'get-detail' => ['get'],
            'add-hand-ready' => ['post'],
            'save-apply-hand' => ['post'],
            'get-hand-list' => ['get'],
            'get-week-hand-rank-list' => ['get'],
            'hand-vote' => ['post'],
            'get-rank-hand' => ['get'],
            'check-user-join' => ['get'],
            'get-activity-by-code' => ['get'],
            'join-welcome-activity' => ['post'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-new' => ['class' => GetNew::class],
            'get-detail' => ['class' => GetDetail::class],
            'add-hand-ready' => ['class' => AddHandReady::class],
            'save-apply-hand' => ['class' => SaveApplyHand::class],
            'get-hand-list' => ['class' => GetHandList::class],
            'get-week-hand-rank-list' => ['class' => GetWeekHandRankList::class],
            'hand-vote' => ['class' => HandVote::class],
            'get-rank-hand' => ['class' => GetRankHand::class],
            'check-user-join' => ['class' => CheckUserJoin::class],
            'get-activity-by-code' => ['class' => GetActivityByCode::class],
            'join-welcome-activity' => ['class' => JoinWelcomeActivity::class],
        ];
    }

}
