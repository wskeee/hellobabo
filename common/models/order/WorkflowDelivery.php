<?php

namespace common\models\order;

use common\models\AdminUser;
use common\utils\I18NUitl;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%workflow_delivery}}".
 *
 * @property int $id
 * @property int $order_id 订单id,关联order,id
 * @property string $order_sn 订单编号,关联order,order_sn
 * @property int $user_id 订单创建人，关联user,id
 * @property string $consignee 收货人
 * @property string $zipcode 邮编
 * @property string $phone 联系手机
 * @property int $country 国ID
 * @property int $province 省ID，关联region,id
 * @property int $city 市ID，关联region,id
 * @property int $district 区ID，关联region,id
 * @property int $town 镇ID，关联region,id
 * @property string $address 详细地址
 * @property string $user_note 用户留言
 * @property string $shipping_code 物流code，关联shipping,code
 * @property string $shipping_name 物流名称
 * @property string $shipping_price 运费
 * @property string $invoice_no 物流单号
 * @property string $note '用户留言'
 * @property int $status 状态'
 * @property int $send_type 发货方式 0自填快递 1无需物流
 * @property int $worker_id 发货人ID，关联admin_user,id
 * @property int $start_at 开始时间
 * @property int $end_at   发货时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * 
 * @property Order $order 订单
 * @property AdminUser $worker 发货员
 */
class WorkflowDelivery extends ActiveRecord
{

    const STATUS_WAIT_START = 0;   //待开始
    const STATUS_RUNGING = 1;       //未完成
    const STATUS_ENDED = 2;         //已完成
    const SEND_TYPE_GENERAL = 0; //0自填快递 
    const SEND_TYPE_NONE = 1; //1无需物流

    /**
     * 状态名称
     * @var type 
     */

    public static $statusNameMap = [
        self::STATUS_WAIT_START => '待开始',
        self::STATUS_RUNGING => '制作中',
        self::STATUS_ENDED => '已完成',
    ];

    /**
     * 发货方式
     * @var type 
     */
    public static $sendTypeNameMap = [
        self::SEND_TYPE_GENERAL => '自填快递',
        self::SEND_TYPE_NONE => '无需物流',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workflow_delivery}}';
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
            [['id', 'order_id', 'user_id', 'country', 'province', 'city', 'district', 'town', 'send_type', 'worker_id', 'start_at', 'end_at', 'status', 'created_at', 'updated_at'], 'integer'],
            [['shipping_code','shipping_price','invoice_no'], 'required'],
            [['shipping_price'], 'number'],
            [['note','user_note'], 'string'],
            [['order_sn', 'phone'], 'string', 'max' => 20],
            [['consignee', 'shipping_name'], 'string', 'max' => 64],
            [['zipcode'], 'string', 'max' => 6],
            [['address', 'invoice_no'], 'string', 'max' => 255],
            [['shipping_code'], 'string', 'max' => 32],
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
            'order_id' => Yii::t('app', 'Order ID'),
            'order_sn' => Yii::t('app', 'Order Sn'),
            'user_id' => Yii::t('app', 'User ID'),
            'consignee' => Yii::t('app', 'Consignee'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'phone' => Yii::t('app', 'Phone'),
            'country' => Yii::t('app', 'Country'),
            'province' => Yii::t('app', 'Province'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'town' => Yii::t('app', 'Town'),
            'address' => Yii::t('app', 'Address'),
            'user_note' => Yii::t('app', 'User Note'),
            'shipping_code' => Yii::t('app', 'Shipping Code'),
            'shipping_name' => Yii::t('app', 'Shipping'),
            'shipping_price' => Yii::t('app', 'Shipping Price'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'Shipping At'),
            'invoice_no' => Yii::t('app', 'Invoice No'),
            'note' => Yii::t('app', 'Remark'),
            'status' => Yii::t('app', 'Status'),
            'send_type' => I18NUitl::t('app', '{Delivery}{Type}'),
            'worker_id' => Yii::t('app', 'Shipper'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 操作人
     * @return QueryRecord
     */
    public function getWorker()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'worker_id']);
    }

    /**
     * 订单
     * @return QueryRecord
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

}
