<?php

namespace common\models\order;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property int $id ID
 * @property string $title 优惠券标题（有图片则显示图片）：无门槛50元优惠券 | 单品最高减2000元
 * @property string $icon_url 图片路径
 * @property string $des 描述
 * @property int $used 可用于：1新人平台券 2平台优惠券 3类目优惠券 4商品优惠券 10 优惠码卷(平台看不到)
 * @property int $type 1满减券 2叠加满减券 3无门槛券（需要限制大小）
 * @property int $with_special 1可用于特价商品 2不能  默认不能(商品优惠卷除外)
 * @property int $with_id 指定使用的类目、商品ID
 * @property float $with_amount 满多少金额可用
 * @property float $used_amount 用券金额
 * @property int $quota 配额：发券数量
 * @property int $user_max_count 用户限领多少张卷
 * @property int $take_count 已领取的优惠券数量
 * @property int $used_count 已使用的优惠券数量
 * @property int $start_time 发放开始时间
 * @property int $end_time 发放结束时间
 * @property int $valid_type 时效:1绝对时效（领取后XXX-XXX时间段有效）? 2相对时效（领取后N天有效）
 * @property int $valid_start_time 使用开始时间
 * @property int $valid_end_time 使用结束时间
 * @property int $valid_days 自领取之日起有效天数
 * @property int $status 0未发布 1生效 2失效 3已结束
 * @property int $created_by 创建用户 关联admin_user id
 * @property int $updated_by 更新用户ID 关联 admin_user id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property array<CouponSwap> swaps 兑换码
 */
class Coupon extends ActiveRecord
{
    const TYPE_FULL = 1;
    const TYPE_FULL_OVERLAY = 2;
    const TYPE_NO_THRESHOLD = 3;
    public static $typeNames = [
        self::TYPE_FULL => '满减卷',
        //self::TYPE_FULL_OVERLAY => '叠加满减券',
        self::TYPE_NO_THRESHOLD => '无门槛券',
    ];

    const USED_NEWER = 1;
    const USED_PLATFORM = 2;
    const USED_TYPE = 3;
    const USED_GOODS = 4;
    const USED_CODE = 10;
    public static $usedNames = [
        self::USED_NEWER => '新人券',
        self::USED_PLATFORM => '平台券',
        //self::USED_TYPE => '类目优惠券',
        self::USED_GOODS => '绘本券',
        self::USED_CODE => '优惠码卷',
    ];

    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_SOLDOUT = 2;
    const STATUS_END = 3;
    public static $statusNames = [
        self::STATUS_UNPUBLISHED => '未发布',
        self::STATUS_PUBLISHED => '生效',
        self::STATUS_SOLDOUT => '失效',
        self::STATUS_END => '已结束',
    ];

    const VALID_TYPE_ABSOLUTE = 1;
    const VALID_TYPE_RELATIVE = 2;
    public static $validTypeNames = [
        self::VALID_TYPE_ABSOLUTE => '绝对',
        self::VALID_TYPE_RELATIVE => '相对',
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coupon}}';
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
            [['used', 'type', 'with_special', 'with_id', 'quota', 'user_max_count', 'take_count', 'used_count', 'with_amount', 'valid_type', 'valid_days', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['used_amount',], 'number'],
            [['title'], 'string', 'max' => 64],
            [['icon_url', 'des'], 'string', 'max' => 255],
            ['start_time', 'filter', 'filter' => function () {
                $hasTime = strpos($this->start_time, ' ') !== false;
                return empty($this->start_time) ? 0 : strtotime($this->start_time . ($hasTime ? '' : ' 00:00:00'));
            }],
            ['end_time', 'filter', 'filter' => function () {
                $hasTime = strpos($this->end_time, ' ') !== false;
                return empty($this->end_time) ? 0 : strtotime($this->end_time . ($hasTime ? '' : ' 23:59:59'));
            }],
            ['valid_start_time', 'filter', 'filter' => function () {
                !empty($this->valid_start_time) && $hasTime = strpos($this->valid_start_time, ' ') !== false;
                return empty($this->valid_start_time) ? 0 : strtotime($this->valid_start_time . ($hasTime ? '' : ' 00:00:00'));
            }],
            ['valid_end_time', 'filter', 'filter' => function () {
                !empty($this->valid_end_time) && $hasTime = strpos($this->valid_end_time, ' ') !== false;
                return empty($this->valid_end_time) ? 0 : strtotime($this->valid_end_time . ($hasTime ? '' : ' 23:59:59'));
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'icon_url' => Yii::t('app', 'Icon'),
            'des' => Yii::t('app', 'Des'),
            'used' => Yii::t('app', 'Used'),
            'type' => Yii::t('app', 'Type'),
            'with_special' => Yii::t('app', 'With Special'),
            'with_id' => Yii::t('app', 'With ID'),
            'with_amount' => Yii::t('app', 'With Amount'),
            'used_amount' => Yii::t('app', 'Used Amount'),
            'quota' => Yii::t('app', 'Quota'),
            'user_max_count' => Yii::t('app', 'User Max Count'),
            'take_count' => Yii::t('app', 'Take Count'),
            'used_count' => Yii::t('app', 'Used Count'),
            'start_time' => Yii::t('app', 'Start Grant Time'),
            'end_time' => Yii::t('app', 'End Grant Time'),
            'valid_type' => Yii::t('app', 'Valid Type'),
            'valid_start_time' => Yii::t('app', 'Valid Start Time'),
            'valid_end_time' => Yii::t('app', 'Valid End Time'),
            'valid_days' => Yii::t('app', 'Valid Days'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function afterFind()
    {
        $this->start_time = $this->start_time == 0 ? '' : date('Y-m-d H:i:s', $this->start_time);
        $this->end_time = $this->end_time == 0 ? '' : date('Y-m-d H:i:s', $this->end_time);
        $this->valid_start_time = $this->valid_start_time == 0 ? '' : date('Y-m-d H:i:s', $this->valid_start_time);
        $this->valid_end_time = $this->valid_end_time == 0 ? '' : date('Y-m-d H:i:s', $this->valid_end_time);
        parent::afterFind();
    }

    /**
     * @return ActiveQuery
     */
    public function getSwaps()
    {
        return $this->hasMany(CouponSwap::class, ['coupon_id', 'id']);
    }
}
