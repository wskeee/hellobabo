<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_record}}".
 *
 * @property int $id
 * @property int $goods_id 商品id，关联goods表id字段
 * @property int $count 描述/简介
 * @property int $time 时间（现只记录每月数据，即为每月第一天0时）
 * @property string $type 类型 click,share,like,sale
 *
 * @property Goods $goods
 * @property GoodsFavorites[] $ids
 */
class GoodsRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id', 'count', 'time'], 'integer'],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', '商品id，关联goods表id字段'),
            'count' => Yii::t('app', '描述/简介'),
            'time' => Yii::t('app', '时间（现只记录每月数据，即为每月第一天0时）'),
            'type' => Yii::t('app', '类型 click,share,like,sale'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(GoodsFavorites::className(), ['goods_id' => 'id'])->viaTable('{{%goods}}', ['id' => 'id']);
    }
}
