<?php

namespace common\models\order;

use common\components\redis\RedisService;
use common\models\goods\Goods;
use common\models\platform\WalletLog;
use common\models\system\Config;
use common\models\User;
use common\utils\I18NUitl;
use Yii;
use yii\base\UserException;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property string $order_sn 订单编号，eg：201812131415221234
 * @property int $goods_id  商品（媒体）ID，关联media表id字段
 * @property string $goods_name 订单名称
 * @property string $goods_price 商品价格
 * @property int $goods_num 购买数
 * @property int $scene_num 购买场景数
 * @property int $spec_id 规格id
 * @property string $spec_key 商品规格key
 * @property string $spec_key_name 规格对应的中文名字
 * @property string $order_amount 应付金额（商品总价-折扣）
 * @property int $order_status 状态 0待付款 5待准备 15待制作 20待发货 25待确认 30已完成 35已取消 99已作废
 * @property int $work_status 制作状态：0未准备 5待设计 6设计中 10待印刷 11印刷中 20已完成
 * @property string $user_note 用户留言
 * @property string $pay_code 付款方式标识，如：alpay
 * @property string $pay_sn 付款流水号
 * @property int $pay_at 付款时间
 * @property int $init_at 初始时间
 * @property int $upload_finish_at 上图时间
 * @property int $design_at 设计时间
 * @property int $print_at 印刷时间
 * @property int $shipping_at 发货时间
 * @property int $confirm_at 确认时间
 * @property int $address_id 地址ID
 * @property string $consignee 收货人
 * @property string $zipcode 邮编
 * @property string $phone 联系手机
 * @property int $country 国ID
 * @property int $province 省ID，关联region,id
 * @property int $city 市ID，关联region,id
 * @property int $district 区ID，关联region,id
 * @property int $town 镇ID，关联region,id
 * @property string $address 详细地址
 * @property int $is_recommend 是否为别人推荐而生成的订单 0否 1是
 * @property int $recommend_by 推荐人ID,关联user,id
 * @property int $created_by 创建人id（购买人ID），关联user表id字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * 
 * @property User $creater 创建人
 * @property User $referrer 推荐人
 * @property OrderGoodsMaterial[] $orderGoodsMaterials 订单素材
 * @property OrderGoodsScene[] $orderGoodsScenes 订单场景
 * @property OrderAction[] $actionLogs 订单场景
 */
class Order extends ActiveRecord
{
    /* 订单状态 */

    const ORDER_STATUS_WAIT_PAY = 0;          //待付款
    const ORDER_STATUS_PAY_FAIL = 1;          //支付失败
    const ORDER_STATUS_WAIT_INIT = 5;         //待初始
    const ORDER_STATUS_WAIT_UPLOAD_PIC = 10;  //待上图
    const ORDER_STATUS_WAIT_MAKE = 15;        //待制作
    const ORDER_STATUS_WAIT_DELIVER = 20;     //待发货
    const ORDER_STATUS_WAIT_CONFIRM = 25;     //待确认
    const ORDER_STATUS_CONFIRMED = 30;        //已确认,已完成
    const ORDER_STATUS_CANCELED = 35;         //已取消
    const ORDER_STATUS_INVALID = 99;          //已作废

    /* 制作状态 */
    const WORK_STATUS_UNREADY = 0;            //未准备 12设计中 15待印刷 16印刷中 20已完成
    const WORK_STATUS_WAIT_DESIGN = 5;        //待设计
    const WORK_STATUS_DESIGNING = 6;          //设计中
    const WORK_STATUS_WAIT_PRINT = 10;        //待印刷
    const WORK_STATUS_PRINTING = 11;          //印刷中
    const WORK_STATUS_FINISH = 20;            //已完成

    /* 订单状态名 */

    public static $orderStatusNameMap = [
        self::ORDER_STATUS_WAIT_PAY => '待付款',
        self::ORDER_STATUS_PAY_FAIL => '支付失败',
        self::ORDER_STATUS_WAIT_INIT => '待初始',
        self::ORDER_STATUS_WAIT_UPLOAD_PIC => '待上图',
        self::ORDER_STATUS_WAIT_MAKE => '待制作',
        self::ORDER_STATUS_WAIT_DELIVER => '待发货',
        self::ORDER_STATUS_WAIT_CONFIRM => '待确认',
        self::ORDER_STATUS_CONFIRMED => '已确认',
        self::ORDER_STATUS_CANCELED => '已取消',
        self::ORDER_STATUS_INVALID => '已作废',
    ];

    /* 制作状态名 */
    public static $workStatusNameMap = [
        self::WORK_STATUS_UNREADY => '未准备',
        self::WORK_STATUS_WAIT_DESIGN => '待设计',
        self::WORK_STATUS_DESIGNING => '设计中',
        self::WORK_STATUS_WAIT_PRINT => '待印刷',
        self::WORK_STATUS_PRINTING => '印刷中',
        self::WORK_STATUS_FINISH => '已完成',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['order_sn', 'goods_id'], 'required'],
                [['goods_id', 'goods_num', 'scene_num', 'spec_id', 'order_status', 'work_status', 'pay_at', 'init_at', 'upload_finish_at', 'design_at', 'print_at', 'shipping_at', 'confirm_at', 'address_id', 'is_recommend', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
                [['country', 'province', 'city', 'district', 'town',], 'integer'],
                [['zipcode'], 'string', 'max' => 6],
                [['address'], 'string', 'max' => 255],
                [['goods_price', 'order_amount'], 'number'],
                [['order_sn', 'pay_code'], 'string', 'max' => 20],
                [['goods_name', 'spec_key', 'spec_key_name'], 'string', 'max' => 100],
                [['user_note'], 'string', 'max' => 255],
                [['pay_sn'], 'string', 'max' => 50],
                [['consignee'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_sn' => Yii::t('app', 'Order Sn'),
            'goods_id' => Yii::t('app', 'Goods'),
            'goods_name' => Yii::t('app', 'Goods'),
            'goods_price' => Yii::t('app', 'Goods Price'),
            'goods_num' => Yii::t('app', 'Goods Num'),
            'scene_num' => Yii::t('app', 'Scene Num'),
            'spec_id' => Yii::t('app', 'Spec ID'),
            'spec_key' => Yii::t('app', 'Spec Key'),
            'spec_key_name' => Yii::t('app', 'Spec'),
            'order_amount' => I18NUitl::t('app', '{Order}{Amount}'),
            'order_status' => I18NUitl::t('app', '{Order}{Status}'),
            'work_status' => Yii::t('app', 'Work Status'),
            'user_note' => Yii::t('app', 'User Note'),
            'pay_code' => Yii::t('app', 'Pay Code'),
            'pay_sn' => Yii::t('app', 'Pay Sn'),
            'pay_at' => Yii::t('app', 'Pay At'),
            'init_at' => Yii::t('app', 'Init At'),
            'upload_finish_at' => Yii::t('app', 'Upload At'),
            'design_at' => Yii::t('app', 'Design At'),
            'print_at' => Yii::t('app', 'Print At'),
            'shipping_at' => Yii::t('app', 'Shipping At'),
            'confirm_at' => Yii::t('app', 'Confirm At'),
            'consignee' => Yii::t('app', 'Consignee'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'phone' => Yii::t('app', 'Phone'),
            'country' => Yii::t('app', 'Country'),
            'province' => Yii::t('app', 'Province'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'town' => Yii::t('app', 'Town'),
            'address' => Yii::t('app', 'Address'),
            'is_recommend' => Yii::t('app', 'Is Recommend'),
            'recommend_by' => Yii::t('app', 'Recommend By'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 随便生成一个 SN码
     * @return string
     */
    public static function getRandomSN()
    {
        //201904251229250000125
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $key_sn = date('Ymd') . substr($msectime, -6);
        $key = "Order:RandomSN:$key_sn";
        $num = 1;
        $r = RedisService::getRedis();
        //一秒内包括 99999 个自增ID
        if ($r->exists($key)) {
            $num = $r->incr($key);
        } else {
            //不存先创建一个，并设置1分钟过期
            $r->setex($key, 60, 1);
        }
        $orderSn = $key_sn . sprintf('%04d', $num) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 支付
     * @param array $message
     */
    public function pay($message)
    {
        $tran = Yii::$app->db->beginTransaction();
        $order = $this;
        try {
            // 用户是否支付成功
            if ($message['result_code'] === 'SUCCESS') {
                $order->pay_code = 'weixin';
                $order->pay_sn = $message['transaction_id'];
                $order->pay_at = time(); // 更新支付时间为当前时间
                $order->order_status = Order::ORDER_STATUS_WAIT_INIT;

                OrderAction::saveLog([$order->id], '支付成功', "支付方式：{$order->pay_code}");

                // 用户支付失败
            } elseif ($message['result_code'] === 'FAIL') {
                $order->order_status = Order::ORDER_STATUS_PAY_FAIL;
                OrderAction::saveLog([$order->id], '支付失败', "{$message['err_code']}\n{$message['err_code_des']}");
            }
            $order->save(); // 保存订单
            OrderAction::saveLog([$order->id], '付款成功', '');
            $tran->commit();
            return true; // 返回处理完成
        } catch (Exception $ex) {
            $tran->rollBack();
            return false;
        }
    }

    /**
     * 订单确认
     * 
     */
    public function finish()
    {
        if ($this->order_status != self::ORDER_STATUS_WAIT_CONFIRM) {
            return "订单无法确认！当前状态为：".self::$orderStatusNameMap[$this->order_status];
        }
        $time = time();
        //是否为推荐
        $is_recommend = $this->is_recommend;
        /* @var $referrer User */
        $referrer = $is_recommend ? User::findOne(['id' => $this->recommend_by]) : null;
        //商品
        $goods = Goods::findOne(['id' => $this->goods_id]);

        //检查推荐人金额是否正常
        if ($is_recommend && !$referrer->moneyVerification()) {
            //账号余额不对，终止结算
            //...添加日志记录
            OrderAction::saveLog([$order->id], '确认失败', "订单确认失败，发现账号余额不对，账号：{$account->id}");
            return "订单确认失败，发现账号余额不对，请联系客户！";
        }

        $tran = \Yii::$app->db->beginTransaction();
        try {
            //订单
            $this->order_status = self::ORDER_STATUS_CONFIRMED;
            $this->confirm_at = $time;
            $this->ar_save($this);

            //商品
            $goods->sale_count ++;
            $this->ar_save($goods);
            
            //推荐
            if ($is_recommend) {
                $commission = Config::getValue('order_recommend_commission');
                $order_recommend = new OrderRecommend([
                    'order_id' => $this->id,
                    'order_sn' => $this->order_sn,
                    'order_amount' => $this->order_amount,
                    'goods_name' => $this->goods_name,
                    'commission' => $commission,
                    'amount' => round(($commission > 1 ? $commission : $this->order_amount * $commission) * 100 / 100),
                    'recommend_by' => $this->recommend_by,
                    'created_by' => $this->created_by,
                ]);
                $this->ar_save($order_recommend);
                
                $referrer->money += $order_recommend->amount;
                $referrer->makeVerification($referrer->id, $referrer->money);
                $this->ar_save($referrer);

                $wallet_log = new WalletLog([
                    'user_id' => $referrer->id,
                    'type' => WalletLog::TYPE_INCOME,
                    'tran_sn' => $order_recommend->id,
                    'tran_money' => $order_recommend->amount,
                    'money_newest' => $referrer->money,
                    'des' => '推荐奖励',
                ]);
                $this->ar_save($wallet_log);
            }

            //日志
            OrderAction::saveLog([$this->id], '订单确认', '订单已完成！');
            $tran->commit();
            return true;
        } catch (\Exception $ex) {
            $tran->rollBack();
            OrderAction::saveLog([$this->id], '订单确认失败', $ex->getMessage());
            return "订单确认失败：{$ex->getMessage()}";
        }
    }

    /**
     * 模型保存
     * @param ActiveRecord $ar
     * 
     * @throws Exception
     */
    private function ar_save($ar)
    {
        if (!$ar->save()) {
            throw new UserException(implode(",", $ar->getErrorSummary(true)));
        }
    }

    /**
     * 检查是否已经支付
     * @return boolean 
     */
    public function getIsPlyed()
    {
        if (!$this->getIsValid()) {
            //订单无效
            return false;
        } else {
            return $this->order_status != self::ORDER_STATUS_WAIT_PAY && $this->order_status != self::ORDER_STATUS_PAY_FAIL;
        }
    }

    /**
     * 检查订单是否有效
     * @return boolean 
     */
    public function getIsValid()
    {
        return $this->order_status != self::ORDER_STATUS_CANCELED && $this->order_status != self::ORDER_STATUS_INVALID;
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
     * 推荐人
     * @return QueryRecord
     */
    public function getReferrer()
    {
        return $this->hasOne(User::class, ['id' => 'recommend_by']);
    }

    /**
     * 订单素材
     * @return QueryRecord
     */
    public function getOrderGoodsMaterials()
    {
        return $this->hasMany(OrderGoodsMaterial::class, ['order_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * 订单场景 
     * @return QueryRecord
     */
    public function getOrderGoodsScenes()
    {
        return $this->hasMany(OrderGoodsScene::class, ['order_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * 制作日志
     * @return QueryRecord
     */
    public function getActionLogs()
    {
        return $this->hasMany(OrderAction::class, ['order_id' => 'id']);
    }

}
