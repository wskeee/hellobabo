<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_scene}}".
 *
 * @property int $id
 * @property int $goods_id 所属商品，关联goods表id字段
 * @property int $group_id 分组id,关联scene_group,id
 * @property string $name 场景名
 * @property string $effect_url 效果图路径
 * @property string $demo_url 参考路径
 * @property string $source_url 源图路径
 * @property int $sort_order 排序
 * @property int $immutable 不可改变 0否 1是
 * @property int $is_required 是否必选 0否 1是
 * @property int $is_selected 默认选中 0否 1是
 * @property int $is_del 是否删除
 * @property string $des 备注
 */
class GoodsScene extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_scene}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'group_id', 'sort_order', 'immutable', 'is_required', 'is_selected', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['effect_url', 'demo_url', 'source_url', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', '所属商品，关联goods表id字段'),
            'group_id' => Yii::t('app', '分组id,关联scene_group,id'),
            'name' => Yii::t('app', '场景名'),
            'effect_url' => Yii::t('app', '效果图路径'),
            'demo_url' => Yii::t('app', '参考路径'),
            'source_url' => Yii::t('app', '源图路径'),
            'sort_order' => Yii::t('app', '排序'),
            'immutable' => Yii::t('app', '不可改变 0否 1是'),
            'is_required' => Yii::t('app', '是否必选 0否 1是'),
            'is_selected' => Yii::t('app', '默认选中 0否 1是'),
            'is_del' => Yii::t('app', '是否删除'),
            'des' => Yii::t('app', '备注'),
        ];
    }
}
