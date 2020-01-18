<?php

namespace common\models\order;

use common\models\AdminUser;
use common\utils\I18NUitl;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "{{%workflow_print}}".
 *
 * @property int $id
 * @property int $order_id 订单id，关联order表id字段
 * @property string $order_sn 订单编号，关联order,sn
 * @property int $order_goods_id 订单商品ID，关联order_goods,id
 * @property int $status 状态 0未完成，1已完成
 * @property int $start_at 开始时间
 * @property int $end_at 结束时间
 * @property int $worker_id 打印人id,关联admin_user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * 
 * @property Order $order 订单
 * @property OrderGoods $orderGoods 订单商品
 * @property AdminUser $worder 操作人
 */
class WorkflowPrint extends ActiveRecord
{

    const STATUS_WAIT_START = 0;   //待开始
    const STATUS_RUNGING = 1;       //未完成
    const STATUS_CHECK = 2;         //审核
    const STATUS_CHECK_FAIL = 3;    //未完成
    const STATUS_ENDED = 5;         //已完成

    /**
     * 状态名称
     * @var type 
     */

    public static $statusNameMap = [
        self::STATUS_WAIT_START => '待开始',
        self::STATUS_RUNGING => '制作中',
        self::STATUS_CHECK => '审核',
        self::STATUS_CHECK_FAIL => '审核失败',
        self::STATUS_ENDED => '已完成',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workflow_print}}';
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
            [['order_id', 'order_goods_id'], 'required'],
            [['order_id', 'order_goods_id', 'status', 'start_at', 'end_at', 'worker_id', 'created_at', 'updated_at'], 'integer'],
            [['order_sn'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'order_sn' => Yii::t('app', 'Order Sn'),
            'order_goods_id' => I18NUitl::t('app', '{Order}{Goods}'),
            'status' => Yii::t('app', 'Status'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'worker_id' => Yii::t('app', 'Printer'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 操作人
     * @return QueryRecord
     */
    public function getWorker()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'worker_id']);
    }

    /**
     * 订单
     * @return QueryRecord
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
    
    /**
     * 订单商品
     * @return QueryRecord
     */
    public function getOrderGoods()
    {
        return $this->hasOne(OrderGoods::class, ['id' => 'order_goods_id']);
    }

    /**
     * 返回数据统计
     *
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     */
    public static function getStat($start_time, $end_time)
    {
        // 查询对象
        $query = (new Query())->from(self::tableName());

        // 完成查询对象
        $query_complete = clone $query;
        $query_complete->where(['between', 'end_at', $start_time, $end_time]);
        $complete_count = $query_complete->count();

        // 今天创建数
        $create_count = $query->where(['between', 'created_at', $start_time, $end_time])->count();

        return [
            'create_count' => $create_count,
            'complete_count' => $complete_count,
        ];

    }
}
