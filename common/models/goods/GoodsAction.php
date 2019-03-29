<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_action}}".
 *
 * @property int $id
 * @property int $goods_id 商品id，关联goods表id字段
 * @property string $title 操作标题/类型
 * @property string $content 操作内容
 * @property int $created_by 操作人id,关联admin_user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Goods $goods
 */
class GoodsAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_action}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 20],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
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
            'title' => Yii::t('app', '操作标题/类型'),
            'content' => Yii::t('app', '操作内容'),
            'created_by' => Yii::t('app', '操作人id,关联admin_user表id字段'),
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
}
