<?php

namespace common\models\platform;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property int $type_id 分类ID，关联post_type
 * @property string $title 文章标题
 * @property string $content '文章内容'
 * @property int $is_publish 是否发布，0否1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * 
 * @property-read PostType $type 类型
 */
class Post extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['type_id', 'is_publish', 'created_at', 'updated_at'], 'integer'],
                [['content'], 'string'],
                [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return QueryRecord
     */
    public function getType()
    {
        return $this->hasOne(PostType::class, ['id' => 'type_id']);
    }

}
