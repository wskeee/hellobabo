<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%workflow_delivery}}".
 *
 * @property int $id
 * @property int $order_id 订单id,关联order,id
 * @property string $order_sn 订单编号,关联order,order_sn
 * @property int $user_id 订单创建人，关联user,id
 * @property string $consignee 收货人
 * @property string $zipcode 邮编
 * @property string $phone 联系手机
 * @property int $country 国ID
 * @property int $province 省ID，关联region,id
 * @property int $city 市ID，关联region,id
 * @property int $district 区ID，关联region,id
 * @property string $address 详细地址
 * @property string $shipping_code 物流code，关联shipping,code
 * @property string $shipping_name 物流名称
 * @property string $shipping_price 运费
 * @property string $invoice_no 物流单号
 * @property string $note ''
 * @property int $send_type 发货方式 0自填快递 1无需物流
 * @property int $created_by 创建人ID，关联admin_user,id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class WorkflowDelivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workflow_delivery}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_by', 'created_at'], 'required'],
            [['id', 'order_id', 'user_id', 'country', 'province', 'city', 'district', 'send_type', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['shipping_price'], 'number'],
            [['note'], 'string'],
            [['order_sn', 'phone'], 'string', 'max' => 20],
            [['consignee', 'shipping_name'], 'string', 'max' => 64],
            [['zipcode'], 'string', 'max' => 6],
            [['address', 'invoice_no'], 'string', 'max' => 255],
            [['shipping_code'], 'string', 'max' => 32],
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
            'order_id' => Yii::t('app', '订单id,关联order,id'),
            'order_sn' => Yii::t('app', '订单编号,关联order,order_sn'),
            'user_id' => Yii::t('app', '订单创建人，关联user,id'),
            'consignee' => Yii::t('app', '收货人'),
            'zipcode' => Yii::t('app', '邮编'),
            'phone' => Yii::t('app', '联系手机'),
            'country' => Yii::t('app', '国ID'),
            'province' => Yii::t('app', '省ID，关联region,id'),
            'city' => Yii::t('app', '市ID，关联region,id'),
            'district' => Yii::t('app', '区ID，关联region,id'),
            'address' => Yii::t('app', '详细地址'),
            'shipping_code' => Yii::t('app', '物流code，关联shipping,code'),
            'shipping_name' => Yii::t('app', '物流名称'),
            'shipping_price' => Yii::t('app', '运费'),
            'invoice_no' => Yii::t('app', '物流单号'),
            'note' => Yii::t('app', '\'\''),
            'send_type' => Yii::t('app', '发货方式 0自填快递 1无需物流'),
            'created_by' => Yii::t('app', '创建人ID，关联admin_user,id'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
