<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_action}}".
 *
 * @property int $id
 * @property int $order_id 订单id，关联order表id字段
 * @property string $title 操作标题/类型
 * @property string $content 操作内容
 * @property int $created_by 操作人id（0为用户操作）,关联admin_user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class OrderAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_action}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
