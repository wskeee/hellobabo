<?php

namespace common\models\platform;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $id
 * @property int $related_id 相关ID
 * @property int $pid 父级ID，自联 id
 * @property string $title 标题
 * @property string $content 内容
 * @property int $depth 深度 0顶级
 * @property string $thread id路径，可用于查询回复嵌套用/分隔
 * @property int $created_by 创建用户uid 0为游客，关联user,id
 * @property int $is_del 是否删除 0否 1是
 * @property int $is_hide 是否匿名 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property User $creator 创建人
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['related_id', 'pid', 'depth', 'created_by', 'is_del', 'is_hide', 'created_at', 'updated_at'], 'integer'],
            [['thread'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'related_id' => Yii::t('app', 'Related ID'),
            'pid' => Yii::t('app', 'Pid'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'depth' => Yii::t('app', 'Depth'),
            'thread' => Yii::t('app', 'Thread'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_del' => Yii::t('app', 'Is Del'),
            'is_hide' => Yii::t('app', 'Is Hide'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $arr = parent::toArray($fields, $expand, $recursive);
        $arr['creator'] = $this->creator;
        $arr['created_text'] = date('Y-m-d H:i:s', $this->created_at);
        return $arr;
    }
}
