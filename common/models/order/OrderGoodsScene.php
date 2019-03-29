<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_goods_scene}}".
 *
 * @property int $id
 * @property int $order_goods_id 订单商品ID，关联order_goods表id字段
 * @property string $name 场景名
 * @property string $effect_url 效果图路径
 * @property string $demo_url 参考路径
 * @property string $source_url 源图路径
 * @property string $user_img_url 用户上传的图片路径
 * @property string $finish_url 成品地址
 * @property int $sort_order 排序
 * @property int $is_required 是否必选 0否 1是
 * @property int $is_del 是否删除
 * @property string $des 备注
 */
class OrderGoodsScene extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods_scene}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_goods_id', 'sort_order', 'is_required', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['effect_url', 'demo_url', 'source_url', 'user_img_url', 'finish_url', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_goods_id' => Yii::t('app', '订单商品ID，关联order_goods表id字段'),
            'name' => Yii::t('app', '场景名'),
            'effect_url' => Yii::t('app', '效果图路径'),
            'demo_url' => Yii::t('app', '参考路径'),
            'source_url' => Yii::t('app', '源图路径'),
            'user_img_url' => Yii::t('app', '用户上传的图片路径'),
            'finish_url' => Yii::t('app', '成品地址'),
            'sort_order' => Yii::t('app', '排序'),
            'is_required' => Yii::t('app', '是否必选 0否 1是'),
            'is_del' => Yii::t('app', '是否删除'),
            'des' => Yii::t('app', '备注'),
        ];
    }
}
