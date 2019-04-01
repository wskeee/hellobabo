<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;
use yii\redis\ActiveQuery;

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
 * 
 * @property Goods goods 商品
 * @property SceneGroup group 分组
 */
class GoodsScene extends ActiveRecord
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
            [['goods_id', 'group_id', 'name'], 'required'],
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
            'goods_id' => Yii::t('app', 'Goods'),
            'group_id' => Yii::t('app', 'Group'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'demo_url' => Yii::t('app', 'Demo Url'),
            'source_url' => Yii::t('app', 'Source Url'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'immutable' => Yii::t('app', 'Immutable'),
            'is_required' => Yii::t('app', 'Required Img'),
            'is_selected' => Yii::t('app', 'Is Selected'),
            'is_del' => Yii::t('app', 'Is Del'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

    /**
     * 
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::class, ['id' => 'goods_id']);
    }

    /**
     * 
     * @return ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(SceneGroup::class, ['id' => 'group_id']);
    }

}
