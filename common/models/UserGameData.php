<?php

namespace common\models;

use phpDocumentor\Reflection\Types\Self_;
use Yii;

/**
 * This is the model class for table "{{%user_game_data}}".
 *
 * @property int $user_id
 * @property int $play_score 表演得分
 * @property int $play_level 等级 1~5
 * @property string $play_title 头衔
 * @property int $next_score 下个等级分数
 */
class UserGameData extends \yii\db\ActiveRecord
{
    public $play_title;
    public $next_score;

    // 等级分值
    public static $levelScore = [50, 120, 250, 500, 9999];

    // 头衔
    public static $playTitleNames = [
        1 => '跑龙套',
        2 => '三线小明星',
        3 => '二线小明星',
        4 => '一线大明星',
        5 => '影帝',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_game_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['play_score', 'play_level', 'next_score'], 'integer'],
            [['play_title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'play_score' => Yii::t('app', 'Play Score'),
            'play_level' => Yii::t('app', 'Play Level'),
            'play_title' => Yii::t('app', 'Play Title'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->play_level = self::getLevel($this->play_score);
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        $this->play_title = self::$playTitleNames[$this->play_level];
        $this->next_score = self::$levelScore[$this->play_level - 1];
        parent::afterFind();
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $arr = parent::toArray($fields, $expand, $recursive);
        $arr['play_title'] = $this->play_title;
        $arr['next_score'] = $this->next_score;
        return $arr;
    }

    public function getDetail()
    {
        $arr = $this->toArray();
        $arr['play_title'] = $this->play_title;
        $arr['next_score'] = $this->next_score;
        return $arr;
    }

    /**
     * 获取等级
     *
     * @param int $score
     * @return int level
     */
    public static function getLevel($score)
    {
        foreach (self::$levelScore as $level => $max_score) {
            if ($score < $max_score) {
                return $level + 1;
            }
        }
        return 0;
    }
}
