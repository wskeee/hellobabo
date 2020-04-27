<?php

namespace common\models\order;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order_goods_comment}}".
 *
 * @property int $id
 * @property int $order_goods_id 订单商品ID 关联order_goods id
 * @property int $pid 父级ID，自联 id
 * @property string $title 标题
 * @property string $content 内容
 * @property int $depth 深度 0顶级
 * @property string $thread id路径，可用于查询回复嵌套用,分隔
 * @property int $created_by 创建用户0为游客 关联user id
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property User $creater 创建人
 */
class OrderGoodsComment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods_comment}}';
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
            [['order_goods_id', 'pid', 'depth', 'created_by', 'is_del', 'created_at', 'updated_at'], 'integer'],
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
            'order_goods_id' => Yii::t('app', 'Order Goods ID'),
            'pid' => Yii::t('app', 'Pid'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'depth' => Yii::t('app', 'Depth'),
            'thread' => Yii::t('app', 'Thread'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->thread = $this->pid ? ($this->thread == "" ? "$this->pid" : "$this->thread,{$this->pid}") : "";
            }
            return true;
        }
        return false;
    }

    /**
     * @return ActiveQuery
     */
    public function getCreater()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $arr = parent::toArray($fields, $expand, $recursive);
        $arr['create_time'] = date('Y.m.d H:i:s', $this->created_at);
        $arr['creater_name'] = $this->creater->nickname;
        return $arr;
    }
}
