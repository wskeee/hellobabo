<?php

namespace common\models\platform;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sl_daily_visit_trend".
 *
 * @property int $id
 * @property int $session_cnt 打开次数
 * @property int $visit_pv 访问次数
 * @property int $visit_uv 访问人数
 * @property int $visit_uv_new 新用户数
 * @property string $stay_time_uv 人均停留时长 (浮点型，单位：秒)
 * @property string $stay_time_session 次均停留时长 (浮点型，单位：秒)
 * @property string $visit_depth 平均访问深度 (浮点型)
 * @property string $ref_date 日期yyyy-mm-dd
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class DailyVisitTrend extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sl_daily_visit_trend';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'session_cnt', 'visit_pv', 'visit_uv', 'visit_uv_new', 'created_at', 'updated_at'], 'integer'],
            [['stay_time_uv', 'stay_time_session', 'visit_depth'], 'number'],
            [['ref_date'], 'safe'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'session_cnt' => Yii::t('app', 'Session Cnt'),
            'visit_pv' => Yii::t('app', 'Visit Pv'),
            'visit_uv' => Yii::t('app', 'Visit Uv'),
            'visit_uv_new' => Yii::t('app', 'Visit Uv New'),
            'stay_time_uv' => Yii::t('app', 'Stay Time Uv'),
            'stay_time_session' => Yii::t('app', 'Stay Time Session'),
            'visit_depth' => Yii::t('app', 'Visit Depth'),
            'ref_date' => Yii::t('app', 'Ref Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 获取访问趋势
     *
     * @param int $day_num 最近几天
     * @return [[date,count],..]
     */
    public static function getVisitTrend($day_num = 7)
    {
        $data = self::find()
            ->select(['ref_date date', 'session_cnt count'])
            ->where(['between', 'ref_date', date('Y-m-d', strtotime("today -$day_num day")), date('Y-m-d', strtotime("today"))])
            ->asArray()
            ->all();
        return $data;
    }
}
