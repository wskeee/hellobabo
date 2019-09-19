<?php

namespace apiend\components\voterank;

use apiend\models\Response;
use common\components\redis\RedisService;
use common\models\activity\VoteRecord;
use common\models\system\Config;
use common\utils\DateUtil;

/**
 * 投票服务
 *
 * @author Administrator
 */
class VoteService
{
    /* 活动 */

    const REDIS_KEY_HAND_NUM = 'activitys:vote:hand_num:{aid}';
    /* 参赛用户日获取票数 */
    const REDIS_KEY_HAND_DAY_COUNT = 'activitys:vote:count:day:{aid}:';
    /* 参赛用户周获取票数 */
    const REDIS_KEY_HAND_WEEK_COUNT = 'activitys:vote:count:week:{aid}:';
    /* 参赛用户月获取票数 */
    const REDIS_KEY_HAND_MONTH_COUNT = 'activitys:vote:count:month:{aid}:';
    /* 参赛用户总获取票数 */
    const REDIS_KEY_HAND_ALL_COUNT = 'activitys:vote:count:all:{aid}';
    /* 投票记录 */
    const REDIS_KEY_HAND_USER_RECORD = 'activitys:vote:record:{aid}:';
    /* 用户日投票数 */
    const REDIS_KEY_HAND_USER_VOTE_COUNT = 'activitys:vote:usercount:{aid}:';

    /**
     * 初始投票
     * 
     * @param int $aid      活动ID
     * @param int $hid      参赛ID
     */
    public static function initVote($aid, $hid)
    {
        $r = RedisService::getRedis();
        // 增加选手日、周、月投票数
        $hand_day_count_key = self::getKey(self::REDIS_KEY_HAND_DAY_COUNT, ['aid' => $aid]) . date('Ymd');
        $hand_week_count_key = self::getKey(self::REDIS_KEY_HAND_WEEK_COUNT, ['aid' => $aid]) . date('Ym') . '_' . DateUtil::getWeekOfMonth();
        $hand_month_count_key = self::getKey(self::REDIS_KEY_HAND_MONTH_COUNT, ['aid' => $aid]) . date('Ym');
        $hand_all_count_key = self::getKey(self::REDIS_KEY_HAND_ALL_COUNT, ['aid' => $aid]);
        $r->zadd($hand_day_count_key, $hid);
        $r->zadd($hand_week_count_key, $hid);
        $r->zadd($hand_month_count_key, $hid);
        $r->zadd($hand_all_count_key, $hid);
    }

    /**
     * 投票
     * 
     * @param int $aid      活动ID
     * @param int $hid      参赛ID
     * @param int $uid      投票人ID
     */
    public static function vote($aid, $hid, $uid)
    {
        $r = RedisService::getRedis();
        // 日最大投票数
        $max_vote_count = Config::getValue('activity_vote_day_max_count');
        // 检查当前用户是否超出最大投票数
        $user_max_vote_count_key = self::getKey(self::REDIS_KEY_HAND_USER_VOTE_COUNT, ['aid' => $aid]) . date('Ymd');

        if (($count = $r->zscore($user_max_vote_count_key, $uid)) >= $max_vote_count) {
            return new Response(Response::CODE_VOTE_OVER_DAY_MAX, null, null, ['param' => $max_vote_count]);
        }

        // 用户投票记录
        $user_record_key = self::getKey(self::REDIS_KEY_HAND_USER_RECORD, ['aid' => $aid]) . date('Ymd');
        $user_record_member = "{$hid}_{$uid}";

        if ($r->sismember($user_record_key, $user_record_member)) {
            // 已经投票
            return new Response(Response::CODE_COMMON_DATA_REPEAT, '您已经投票，请勿重复投票！');
        } else {
            // 增加选手日、周、月投票数
            $hand_day_count_key = self::getKey(self::REDIS_KEY_HAND_DAY_COUNT, ['aid' => $aid]) . date('Ymd');
            $hand_week_count_key = self::getKey(self::REDIS_KEY_HAND_WEEK_COUNT, ['aid' => $aid]) . date('Ym') . '_' . DateUtil::getWeekOfMonth();
            $hand_month_count_key = self::getKey(self::REDIS_KEY_HAND_MONTH_COUNT, ['aid' => $aid]) . date('Ym');
            $hand_all_count_key = self::getKey(self::REDIS_KEY_HAND_ALL_COUNT, ['aid' => $aid]);
            $r->zincrby($hand_day_count_key, 1, $hid);
            $r->zincrby($hand_week_count_key, 1, $hid);
            $r->zincrby($hand_month_count_key, 1, $hid);
            $r->zincrby($hand_all_count_key, 1, $hid);

            // 增加 reids 记录
            $r->sadd($user_record_key, $user_record_member);

            // 记录用户今天投票数
            $r->zincrby($user_max_vote_count_key, 1, $uid);

            // 增加 mysql 记录
            $hand_vote_record = new VoteRecord([
                'vote_user_id' => $uid,
                'activity_hand_id' => $hid,
                'add_date' => date('Y-m-d', time()),
            ]);
            $hand_vote_record->save();

            return new Response(Response::CODE_COMMON_OK);
        }
    }

    /**
     * 获取用户是否对 hid 选手已经投过票
     * 
     * @param int $aid
     * @param int $hid
     * @param int $uid
     */
    public static function getUserIsVote($aid, $hid, $uid)
    {
        // 用户投票记录
        $user_record_key = self::getKey(self::REDIS_KEY_HAND_USER_RECORD, ['aid' => $aid]) . date('Ymd');
        $user_record_member = "{$hid}_{$uid}";
        $r = RedisService::getRedis();

        return $r->sismember($user_record_key, $user_record_member);
    }

    /**
     * 获取选手指定日期选票
     * 
     * @param int $aid      活动ID
     * @param int $hid      选手参选ID
     * @param string $day   日期 20190901
     */
    public static function getHandDayCount($aid, $hid, $day = null)
    {
        if ($day == null) {
            $day = date('Ymd', time());
        }
        $r = RedisService::getRedis();
        $key = self::getKey(self::REDIS_KEY_HAND_DAY_COUNT, ['aid' => $aid]) . $day;
        return $r->zscore($key, $hid);
    }

    /**
     * 获取选手指定周选票
     * 
     * @param int $aid      活动ID
     * @param int $hid      选手参选ID
     * @param int $month    月份 201904
     * @param int $week     1~6 周一致周日为一周
     */
    public static function getHandWeekCount($aid, $hid, $month = null, $week = null)
    {
        if ($month == null) {
            $month = date('Ym');
        }
        if ($week == null) {
            $week = DateUtil::getWeekOfMonth();
        }
        $r = RedisService::getRedis();
        $key = self::getKey(self::REDIS_KEY_HAND_WEEK_COUNT, ['aid' => $aid]) . $month . '_' . $week;

        return $r->zscore($key, $hid);
    }

    /**
     * 获取选手指定月选票
     * 
     * @param int $aid          活动ID
     * @param int $hid          选手参选ID
     * @param string $month     月份 201904
     */
    public static function getHandMonthCount($aid, $hid, $month = null)
    {
        if ($month == null) {
            $month = date('Ym');
        }
        $r = RedisService::getRedis();
        $key = self::getKey(self::REDIS_KEY_HAND_MONTH_COUNT, ['aid' => $aid]) . $month;

        return $r->zscore($key, $hid);
    }

    /**
     * 获取选手本次活动总票数
     * 
     * @param int $aid      活动ID
     * @param int $hid      参选ID
     */
    public static function getHandAllCount($aid, $hid)
    {
        $r = RedisService::getRedis();
        $key = self::getKey(self::REDIS_KEY_HAND_ALL_COUNT, ['aid' => $aid]);

        return $r->zscore($key, $hid);
    }

    /**
     * 获取选手指定周选票情况
     * 
     * @param int $aid      活动ID
     * @param int $month    月份 201904
     * @param int $week     1~6 周一致周日为一周
     * @param int $start        开始排序
     * @param int $end          结束排序
     */
    public static function getHandWeekInfo($aid, $month = null, $week = null, $start = 0, $end = 2)
    {
        if ($month == null) {
            $month = date('Ym');
        }
        if ($week == null) {
            $week = DateUtil::getWeekOfMonth();
        }
        $r = RedisService::getRedis();
        $key = self::getKey(self::REDIS_KEY_HAND_WEEK_COUNT, ['aid' => $aid]) . $month . '_' . $week;

        $info = [
            'rank' => self::convertRange($r->zrevrange($key, $start, $end, true)),
        ];

        return $info;
    }

    /**
     * 获取选手指定月选票情况
     * 
     * @param int $aid          活动ID
     * @param string $month     月份 201904
     * @param int $start        开始排序
     * @param int $end          结束排序
     */
    public static function getHandMonthInfo($aid, $month = null, $start = 0, $end = 4)
    {
        if ($month == null) {
            $month = date('Ym');
        }
        $r = RedisService::getRedis();
        $key = self::getKey(self::REDIS_KEY_HAND_MONTH_COUNT, ['aid' => $aid]) . $month;

        $info = [
            'rank' => self::convertRange($r->zrevrange($key, $start, $end, true)),
        ];
        return $info;
    }

    /**
     * 获取选手本次活动总票数情况
     * 
     * @param int $aid          活动ID
     * @param int $start        开始排序
     * @param int $end          结束排序
     */
    public static function getHandAllInfo($aid, $start = 0, $end = 4)
    {
        $r = RedisService::getRedis();
        $key = self::getKey(self::REDIS_KEY_HAND_ALL_COUNT, ['aid' => $aid]);

        $info = [
            'rank' => self::convertRange($r->zrevrange($key, $start, $end, true)),
            'max_hand' => $r->zcard($key),
        ];

        return $info;
    }

    /**
     * 转换 zrange 的数据
     * 
     * @param type $arr
     */
    private static function convertRange($arr)
    {
        $target = [];
        for ($i = 0, $len = count($arr); $i < $len; $i += 2) {
            $target[$arr[$i]] = $arr[$i + 1];
        }

        return $target;
    }

    /**
     * 获取参选序号
     * 
     * @param type $activity_id
     */
    public static function getNum($activity_id)
    {
        $key = self::getKey(self::REDIS_KEY_HAND_ALL_COUNT, ['aid' => $activity_id]);
        $r = RedisService::getRedis();
        //一秒内包括 99999 个自增ID
        if (!$r->exists($key)) {
            //不存先创建一个，并设置1个月过期
            $r->setex($key, 60 * 24 * 32, 0);
        }
        $num = $r->incr($key);
        return $num;
    }

    /**
     * 获取key
     * 
     * @param string $key   带{p}参数的字符串
     * @param array $params 可替换的参数
     */
    private static function getKey($key, $params)
    {
        //如果params不为空，将替换消息里的动态参数
        if ($params != null) {
            foreach ($params as $item => $value) {
                $key = preg_replace("/\{$item\}/", $value, $key);
            }
        }
        return $key;
    }

}
