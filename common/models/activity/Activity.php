<?php

namespace common\models\activity;

use common\utils\I18NUitl;
use Yii;

/**
 * This is the model class for table "{{%activity}}".
 *
 * @property int $id
 * @property string $type 类型
 * @property string $name 名称
 * @property string $title 标题
 * @property string $code 活动编码
 * @property string $cover_url 封面路径
 * @property string $share_poster_url 分享海报路径
 * @property string $show_urls 移动内容
 * @property string $content 内容
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $status 状态 1已发布 2已下架
 * @property string $setting 活动设置
 * @property int $view_count 查看人数
 * @property int $join_count 参加人数
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * 活动类型
     * @var array
     */
    public static $typeNames = [
        'common' => '公共',
        'welcome' => '新人',
    ];

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    public static $statusNames = [
        self::STATUS_ENABLED => '已发布',
        self::STATUS_DISABLED => '已下架'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%activity}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[], 'required'],
            [['type','content', 'setting'], 'string'],
            [['status', 'view_count', 'join_count'], 'integer'],
            [['name', 'code', 'start_time', 'end_time'], 'string', 'max' => 50],
            [['title', 'cover_url', 'share_poster_url'], 'string', 'max' => 255],
            [['show_urls'], 'arrTostr'],
            ['start_time', 'filter', 'filter' => function () {
                $hasTime = strpos($this->start_time, ' ') !== false;
                return empty($this->start_time) ? 0 : strtotime($this->start_time . ($hasTime ? '' : ' 00:00:00'));
            }],
            ['end_time', 'filter', 'filter' => function () {
                $hasTime = strpos($this->end_time, ' ') !== false;
                return empty($this->end_time) ? 0 : strtotime($this->end_time . ($hasTime ? '' : ' 23:59:59'));
            }],
        ];
    }

    /**
     * 数组转字符
     *
     * @param string|array $att
     * @return boolean
     */
    public function arrTostr($att)
    {
        $value = $this[$att];
        if (is_array($value)) {
            $value = implode(',', array_filter($value));
        }
        $this[$att] = $value;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'code' => Yii::t('app', 'Code'),
            'cover_url' => Yii::t('app', 'Cover'),
            'share_poster_url' => I18NUitl::t('app', '{Share}{Poster}'),
            'show_urls' => I18NUitl::t('app', '内容展示'),
            'content' => Yii::t('app', 'Content'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'status' => Yii::t('app', 'Status'),
            'setting' => Yii::t('app', 'Setting'),
            'view_count' => I18NUitl::t('app', '{View}{Count}'),
            'join_count' => I18NUitl::t('app', '{Join}{Count}'),
        ];
    }

    public function afterFind()
    {
        $this->start_time = $this->start_time == 0 ? '' : date('Y-m-d H:i:s', $this->start_time);
        $this->end_time = $this->end_time == 0 ? '' : date('Y-m-d H:i:s', $this->end_time);
        parent::afterFind();
    }
}
