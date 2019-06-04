<?php

namespace common\models\platform;

use common\models\AdminUser;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%issue}}".
 *
 * @property string $id
 * @property string $order_id 订单ID，关联goods表id字段
 * @property string $order_sn 订单编码
 * @property string $goods_id 商品ID，关联goods表id字段
 * @property int $result 处理结果 0未解决 1已解决
 * @property int $status 处理状态 0未处理 1已处理
 * @property string $feedback 处理反馈
 * @property int $type 问题类型 1设备故障 2商家服务 9其它
 * @property string $content 问题内容
 * @property string $content_imgs 内容图片
 * @property string $contact_phone 联系电话
 * @property string $contact_name 联系人
 * @property string $handled_by 处理人ID，关联admin_user表id字段
 * @property string $handled_at 处理时间
 * @property string $created_by 创建人（提交人），关联user表id字段
 * @property string $created_at 创建时间（提交时间）
 * @property string $updated_at 更新时间
 * 
 * @property-read User $creater 创建人
 * @property-read AdminUser $handler 处理人
 */
class Issue extends ActiveRecord
{
    /* 类型 */

    const TYPE_SHOPPING = 1;        //购物体验
    const TYPE_ORDER = 2;           //设备
    const TYPE_MERCHANT = 3;        //商家
    const TYPE_OTHER = 9;           //其它

    public static $typeNameMap = [
        self::TYPE_SHOPPING => '购物体验',
        self::TYPE_ORDER => '订单问题',
        self::TYPE_MERCHANT => '售后服务',
        self::TYPE_OTHER => '其它',
    ];

    /* 处理状态 */

    const STATUS_NO = 0;           //未处理
    const STATUS_YES = 1;          //已处理

    public static $statusNameMap = [
        self::STATUS_NO => '未处理',
        self::STATUS_YES => '已处理',
    ];

    /* 处理结果 */

    const RESULT_NO = 0;           //未解决
    const RESULT_YES = 1;          //已解决

    public static $resultNameMap = [
        self::RESULT_NO => '未解决',
        self::RESULT_YES => '已解决',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%issue}}';
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
                [['goods_id', 'order_id', 'result', 'status', 'type', 'handled_by', 'handled_at', 'created_by', 'created_at', 'updated_at'], 'integer'],
                [['feedback', 'content', 'content_imgs', 'contact_name', 'contact_phone'], 'string'],
                [['order_sn'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', 'Goods'),
            'order_id' => Yii::t('app', 'Order'),
            'order_sn' => Yii::t('app', 'Order Sn'),
            'result' => Yii::t('app', 'Result'),
            'status' => Yii::t('app', 'Status'),
            'feedback' => Yii::t('app', 'Feedback'),
            'type' => Yii::t('app', 'Type'),
            'content' => Yii::t('app', 'Content'),
            'content_imgs' => Yii::t('app', 'Content Imgs'),
            'contact_name' => Yii::t('app', 'Contact Name'),
            'contact_phone' => Yii::t('app', 'Contact Phone'),
            'handled_by' => Yii::t('app', 'Handled By'),
            'handled_at' => Yii::t('app', 'Handled At'),
            'created_by' => Yii::t('app', 'Issue By'),
            'created_at' => Yii::t('app', 'Issue At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 
     * @return QueryRecord
     */
    public function getCreater()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * 
     * @return QueryRecord
     */
    public function getHandler()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'handled_by']);
    }

}
