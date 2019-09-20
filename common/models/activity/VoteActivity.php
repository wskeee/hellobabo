<?php

namespace common\models\activity;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%vote_activity}}".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $cover_url 封面路径
 * @property string $share_poster_url 分享海报路径
 * @property string $content 内容
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $is_publish 是否发布
 */
class VoteActivity extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_activity}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_publish'], 'integer'],
            [['content', 'start_time', 'end_time'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['cover_url','share_poster_url'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'cover_url' => Yii::t('app', 'Cover'),
            'share_poster_url' => Yii::t('app', 'Poster'),
            'content' => Yii::t('app', 'Content'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'is_publish' => Yii::t('app', 'Is Publish'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (is_string($this->start_time)) {
                $this->start_time = strtotime($this->start_time);
            }
            if (is_string($this->end_time)) {
                $this->end_time = strtotime($this->end_time);
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        $this->start_time = date('Y-m-d', $this->start_time == 0 ? time() : $this->start_time);
        $this->end_time = date('Y-m-d', $this->end_time == 0 ? time() : $this->end_time);
    }

}
