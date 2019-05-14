<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property string $order_sn 订单编号，eg：201812131415221234
 * @property int $goods_id  商品（媒体）ID，关联media表id字段
 * @property string $goods_name 订单名称
 * @property string $goods_price 商品价格
 * @property int $goods_num 购买数
 * @property int $spec_id 规格id
 * @property string $spec_key 商品规格key
 * @property string $spec_key_name 规格对应的中文名字
 * @property string $order_amount 应付金额（商品总价-折扣）
 * @property int $order_status 状态 0待付款 5待准备 15待制作 20待发货 25待确认 30已取消 99已作废
 * @property int $work_status 制作状态：0未准备5待初始10待上图11待设计12设计中15待印刷16印刷中20已完成
 * @property string $user_note 用户留言
 * @property string $play_code 付款方式标识，如：alplay
 * @property string $play_sn 付款流水号
 * @property int $play_at 付款时间
 * @property int $init_at 初始时间
 * @property int $upload_finish_at 上图时间
 * @property int $design_at 设计时间
 * @property int $print_at 印刷时间
 * @property int $shipping_at 发货时间
 * @property int $confirm_at 确认时间
 * @property string $consignee 收货人
 * @property int $is_recommend 是否为别人推荐而生成的订单 0否 1是
 * @property int $recommend_by 推荐人ID,关联user,id
 * @property int $created_by 创建人id（购买人ID），关联user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
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
            [['order_sn', 'goods_id'], 'required'],
            [['goods_id', 'goods_num', 'spec_id', 'order_status', 'work_status', 'play_at', 'init_at', 'upload_finish_at', 'design_at', 'print_at', 'shipping_at', 'confirm_at', 'is_recommend', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['goods_price', 'order_amount'], 'number'],
            [['order_sn', 'play_code'], 'string', 'max' => 20],
            [['goods_name', 'spec_key', 'spec_key_name'], 'string', 'max' => 100],
            [['user_note'], 'string', 'max' => 255],
            [['play_sn'], 'string', 'max' => 50],
            [['consignee'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_sn' => Yii::t('app', 'Order Sn'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'goods_name' => Yii::t('app', 'Goods Name'),
            'goods_price' => Yii::t('app', 'Goods Price'),
            'goods_num' => Yii::t('app', 'Goods Num'),
            'spec_id' => Yii::t('app', 'Spec ID'),
            'spec_key' => Yii::t('app', 'Spec Key'),
            'spec_key_name' => Yii::t('app', 'Spec Key Name'),
            'order_amount' => Yii::t('app', 'Order Amount'),
            'order_status' => Yii::t('app', 'Order Status'),
            'work_status' => Yii::t('app', 'Work Status'),
            'user_note' => Yii::t('app', 'User Note'),
            'play_code' => Yii::t('app', 'Play Code'),
            'play_sn' => Yii::t('app', 'Play Sn'),
            'play_at' => Yii::t('app', 'Play At'),
            'init_at' => Yii::t('app', 'Init At'),
            'upload_finish_at' => Yii::t('app', 'Upload Finish At'),
            'design_at' => Yii::t('app', 'Design At'),
            'print_at' => Yii::t('app', 'Print At'),
            'shipping_at' => Yii::t('app', 'Shipping At'),
            'confirm_at' => Yii::t('app', 'Confirm At'),
            'consignee' => Yii::t('app', 'Consignee'),
            'is_recommend' => Yii::t('app', 'Is Recommend'),
            'recommend_by' => Yii::t('app', 'Recommend By'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
