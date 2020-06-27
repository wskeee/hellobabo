<?php

namespace common\models\platform;

use common\models\order\Order;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%agency_order}}".
 *
 * @property int $id
 * @property int $agency_id 代理商ID，关联agency.id
 * @property int $order_id 订单ID，关联order.id
 * @property int $is_self 是否代理商自产 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Order $order;
 * @property Agency $agency;
 */
class AgencyOrder extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agency_order}}';
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
            [['agency_id', 'order_id', 'is_self', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'agency_id' => Yii::t('app', 'Agency'),
            'order_id' => Yii::t('app', 'Order'),
            'is_self' => Yii::t('app', '自身订单'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::class, ['id' => 'agency_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
}
