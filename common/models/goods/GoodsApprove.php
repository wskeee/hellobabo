<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_approve}}".
 *
 * @property int $id
 * @property int $goods_id 商品ID，关联goods表id字段
 * @property int $type 审核类型 1发布 2下架
 * @property string $content 申请说明
 * @property int $status 状态 0待审核 1已审核
 * @property int $result 审核结果 0不通过 1通过
 * @property string $feedback 审核反馈
 * @property int $handled_by 审核人ID，关联admin_user表id
 * @property int $handled_at 审核时间
 * @property int $created_by 申请人ID，关联admin_user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Goods $goods
 */
class GoodsApprove extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_approve}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'type', 'status', 'result', 'handled_by', 'handled_at', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['content', 'feedback'], 'string', 'max' => 255],
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
            'goods_id' => Yii::t('app', '商品ID，关联goods表id字段'),
            'type' => Yii::t('app', '审核类型 1发布 2下架'),
            'content' => Yii::t('app', '申请说明'),
            'status' => Yii::t('app', '状态 0待审核 1已审核'),
            'result' => Yii::t('app', '审核结果 0不通过 1通过'),
            'feedback' => Yii::t('app', '审核反馈'),
            'handled_by' => Yii::t('app', '审核人ID，关联admin_user表id'),
            'handled_at' => Yii::t('app', '审核时间'),
            'created_by' => Yii::t('app', '申请人ID，关联admin_user表id字段'),
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
