<?php

namespace common\models\order;

use common\models\AdminUser;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%workflow_design}}".
 *
 * @property int $id
 * @property int $order_id 订单id，关联order表id字段
 * @property string $order_sn 订单编号,关联order,sn
 * @property int $order_goods_id 订单商品ID,关联order_goods,id
 * @property int $status 状态 0未完成，1已完成
 * @property int $start_at 开始时间
 * @property int $end_at 结束时间
 * @property int $worker_id 设计人ID，关联admin_user,id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * 
 * @property Order $order 订单
 * @property AdminUser $worder 操作人
 */
class WorkflowDesign extends ActiveRecord
{
    const STATUS_WAIT_START = 0;   //待开始
    const STATUS_RUNGING = 1;       //未完成
    const STATUS_ENDED = 2;         //已完成
    
    /**
     * 状态名称
     * @var type 
     */
    public static $statusNameMap = [
        self::STATUS_WAIT_START => '待开始',
        self::STATUS_RUNGING => '制作中',
        self::STATUS_ENDED => '已完成',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workflow_design}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'status', 'start_at', 'end_at', 'worker_id', 'created_at', 'updated_at'], 'integer'],
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
            'status' => Yii::t('app', 'Status'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'worker_id' => Yii::t('app', 'Worker'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 操作人
     * @return QueryRecord
     */
    public function getWorker(){
        return $this->hasOne(AdminUser::class, ['id' => 'worker_id']);
    }
    
    /**
     * 订单
     * @return QueryRecord
     */
    public function getOrder(){
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
}
