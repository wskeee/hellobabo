<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property string $order_sn 订单编号，eg：201812131415221234
 * @property string $order_name 订单名称
 * @property string $goods_amount 商品总价
 * @property string $order_amount 应付金额（商品总价-折扣）
 * @property int $order_status 状态 0待付款 5待准备 15待制作 20待发货 25待确认 30已取消 99已作废
 * @property string $user_note 用户留言
 * @property string $play_code 付款方式标识，如：alplay
 * @property string $play_sn 付款流水号
 * @property int $play_at 付款时间
 * @property int $ready_at 准备时间
 * @property int $work_finish_at 制作完成时间
 * @property int $shipping_at 发货时间
 * @property int $confirm_at 确认时间
 * @property string $consignee 收货人
 * @property int $is_recommend 是否为别人推荐而生成的订单 0否 1是
 * @property int $recommend_by 推荐人ID,关联user,id
 * @property int $created_by 创建人id（购买人ID），关联user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property WorkflowDesign $id0
 * @property OrderGoods $id1
 * @property WorkflowPrint $id2
 * @property OrderRecommend $id3
 * @property OrderAction[] $orderActions
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_amount', 'order_amount'], 'number'],
            [['order_status', 'play_at', 'ready_at', 'work_finish_at', 'shipping_at', 'confirm_at', 'is_recommend', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_sn', 'play_code'], 'string', 'max' => 20],
            [['order_name'], 'string', 'max' => 100],
            [['user_note'], 'string', 'max' => 255],
            [['play_sn'], 'string', 'max' => 50],
            [['consignee'], 'string', 'max' => 64],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkflowDesign::className(), 'targetAttribute' => ['id' => 'order_id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderGoods::className(), 'targetAttribute' => ['id' => 'order_id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkflowPrint::className(), 'targetAttribute' => ['id' => 'order_id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderRecommend::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_sn' => Yii::t('app', '订单编号，eg：201812131415221234'),
            'order_name' => Yii::t('app', '订单名称'),
            'goods_amount' => Yii::t('app', '商品总价'),
            'order_amount' => Yii::t('app', '应付金额（商品总价-折扣）'),
            'order_status' => Yii::t('app', '状态 0待付款 5待准备 15待制作 20待发货 25待确认 30已取消 99已作废'),
            'user_note' => Yii::t('app', '用户留言'),
            'play_code' => Yii::t('app', '付款方式标识，如：alplay'),
            'play_sn' => Yii::t('app', '付款流水号'),
            'play_at' => Yii::t('app', '付款时间'),
            'ready_at' => Yii::t('app', '准备时间'),
            'work_finish_at' => Yii::t('app', '制作完成时间'),
            'shipping_at' => Yii::t('app', '发货时间'),
            'confirm_at' => Yii::t('app', '确认时间'),
            'consignee' => Yii::t('app', '收货人'),
            'is_recommend' => Yii::t('app', '是否为别人推荐而生成的订单 0否 1是'),
            'recommend_by' => Yii::t('app', '推荐人ID,关联user,id'),
            'created_by' => Yii::t('app', '创建人id（购买人ID），关联user表id字段'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(WorkflowDesign::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId1()
    {
        return $this->hasOne(OrderGoods::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId2()
    {
        return $this->hasOne(WorkflowPrint::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId3()
    {
        return $this->hasOne(OrderRecommend::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderActions()
    {
        return $this->hasMany(OrderAction::className(), ['order_id' => 'id']);
    }
}
