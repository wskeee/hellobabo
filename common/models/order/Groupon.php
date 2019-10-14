<?php

namespace common\models\order;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%groupon}}".
 *
 * @property int $id 名称
 * @property string $name
 * @property int $type 类型 1默认
 * @property string $des 描述
 * @property string $cover_url 封面路径
 * @property int $order_id 订单ID，关联order表id字段
 * @property int $order_goods_id 订单商品ID，关联order_goods,id
 * @property int $goods_id 商品ID，关联goods,id
 * @property string $goods_name 商品名称
 * @property string $goods_img 商品图片地址
 * @property string $goods_params 商品参数
 * @property int $spec_id 规格id
 * @property string $spec_key 商品规格key
 * @property string $spec_key_name 规格对应的中文名字
 * @property string $amount 总价
 * @property int $status 状态 1正常 2已完成 99作废
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $created_by 创建人（购买人），关联user表id字段
 * @property int $created_at 创建时间（购买时间）
 * @property int $updated_at 更新时间
 */
class Groupon extends ActiveRecord
{

    // 行动中
    const STATUS_RUNING = 1;
    // 已完成
    const STATUS_FINISHED = 5;
    // 作废
    const STATUS_INVALID = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%groupon}}';
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
            [['name', 'created_by'], 'required'],
            [['type', 'order_id', 'order_goods_id', 'goods_id', 'spec_id', 'status', 'start_time', 'end_time', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['des', 'cover_url', 'goods_img'], 'string', 'max' => 255],
            [['goods_name', 'spec_key', 'spec_key_name'], 'string', 'max' => 100],
            [['goods_params'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'des' => Yii::t('app', 'Des'),
            'cover_url' => Yii::t('app', 'Cover Url'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'goods_name' => Yii::t('app', 'Goods Name'),
            'goods_img' => Yii::t('app', 'Goods Img'),
            'goods_params' => Yii::t('app', 'Params'),
            'spec_id' => Yii::t('app', 'Spec ID'),
            'spec_key' => Yii::t('app', 'Spec Key'),
            'spec_key_name' => Yii::t('app', 'Spec Key Name'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

}
