<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_favorites}}".
 *
 * @property int $id
 * @property int $goods_id 商品ID，关联goods表id字段
 * @property string $group_name 分组名称
 * @property int $is_del 是否已删除 0否 1是
 * @property int $created_by 创建人（收藏人）
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Goods $goods
 * @property GoodsRecord[] $ids
 */
class GoodsFavorites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_favorites}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'created_by'], 'required'],
            [['goods_id', 'is_del', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['group_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', '商品ID，关联goods表id字段'),
            'group_name' => Yii::t('app', '分组名称'),
            'is_del' => Yii::t('app', '是否已删除 0否 1是'),
            'created_by' => Yii::t('app', '创建人（收藏人）'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(GoodsRecord::className(), ['id' => 'id'])->viaTable('{{%goods}}', ['id' => 'goods_id']);
    }
}
