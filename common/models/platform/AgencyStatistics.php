<?php

namespace common\models\platform;

use Yii;

/**
 * This is the model class for table "{{%agency_statistics}}".
 *
 * @property int $id
 * @property int $agency_id 代理商，关联agency.id
 * @property string $time 时间=202010
 * @property int $order_num_all 总订单数
 * @property int $order_num_self
 * @property int $order_num_auto 自动归入
 */
class AgencyStatistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agency_statistics}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agency_id', 'order_num_all', 'order_num_self', 'order_num_auto'], 'integer'],
            [['time'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'agency_id' => Yii::t('app', 'Agency ID'),
            'time' => Yii::t('app', 'Time'),
            'order_num_all' => Yii::t('app', '总订单'),
            'order_num_self' => Yii::t('app', '自身订单'),
            'order_num_auto' => Yii::t('app', '自动归类'),
        ];
    }
}
