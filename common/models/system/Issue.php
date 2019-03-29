<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "{{%issue}}".
 *
 * @property int $id
 * @property int $goods_id 媒体ID，关联media表id字段
 * @property int $result 处理结果 0未解决 1已解决
 * @property int $status 处理状态 0未处理 1已处理
 * @property string $feedback 处理反馈
 * @property int $type 问题类型 1版权 2内容 3标签属性 4访问 5其它
 * @property string $content 问题内容
 * @property int $handled_by 处理人ID，关联admin_user表id字段
 * @property int $handled_at 处理时间
 * @property int $created_by 创建人（提交人），关联user表id字段
 * @property int $created_at 创建时间（提交时间）
 * @property int $updated_at 更新时间
 *
 * @property Goods $goods
 */
class Issue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%issue}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id', 'result', 'status', 'type', 'handled_by', 'handled_at', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['feedback', 'content'], 'string'],
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
            'goods_id' => Yii::t('app', '媒体ID，关联media表id字段'),
            'result' => Yii::t('app', '处理结果 0未解决 1已解决'),
            'status' => Yii::t('app', '处理状态 0未处理 1已处理'),
            'feedback' => Yii::t('app', '处理反馈'),
            'type' => Yii::t('app', '问题类型 1版权 2内容 3标签属性 4访问 5其它'),
            'content' => Yii::t('app', '问题内容'),
            'handled_by' => Yii::t('app', '处理人ID，关联admin_user表id字段'),
            'handled_at' => Yii::t('app', '处理时间'),
            'created_by' => Yii::t('app', '创建人（提交人），关联user表id字段'),
            'created_at' => Yii::t('app', '创建时间（提交时间）'),
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
