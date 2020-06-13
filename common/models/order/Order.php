<?php

namespace common\models\order;

use apiend\modules\v1\actions\order\GetTempOrder;
use common\components\redis\RedisService;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecPrice;
use common\models\platform\WalletLog;
use common\models\platform\Withdrawals;
use common\models\system\Config;
use common\models\User;
use common\models\UserAddress;
use common\utils\I18NUitl;
use Yii;
use yii\base\UserException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property string $order_sn 订单编号，eg：201812131415221234
 * @property string $order_amount 应付金额（商品总价-折扣）
 * @property int $order_status 状态 0待付款 5待准备 15待制作 20待发货 25待确认 30已完成 35已取消 99已作废
 * @property string $goods_amount 商品总价
 * @property string $coupon_amount 优惠金额
 * @property string $user_note 用户留言
 * @property string $pay_code 付款方式标识，如：alpay
 * @property string $pay_sn 付款流水号
 * @property int $pay_at 付款时间
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
 * @property OrderGoods[] $orderGoods 订单绘本
 * @property OrderAction[] $actionLogs 日志记录
 */
class Order extends ActiveRecord
{
    /* 订单状态 状态 0待付款 1付款失败 20待发货 25待确认 30已完成 35已取消 99已作废 */

    const ORDER_STATUS_WAIT_PAY = 0;          //待付款
    const ORDER_STATUS_PAY_FAIL = 1;          //支付失败
    const ORDER_STATUS_WAIT_DELIVER = 20;     //待发货
    const ORDER_STATUS_WAIT_CONFIRM = 25;     //待确认
    const ORDER_STATUS_CONFIRMED = 30;        //已确认,已完成
    const ORDER_STATUS_CANCELED = 35;         //已取消
    const ORDER_STATUS_INVALID = 99;          //已作废

    /* 订单状态名 */

    public static $orderStatusNameMap = [
        self::ORDER_STATUS_WAIT_PAY => '待付款',
        self::ORDER_STATUS_PAY_FAIL => '支付失败',
        self::ORDER_STATUS_WAIT_DELIVER => '待发货',
        self::ORDER_STATUS_WAIT_CONFIRM => '待确认',
        self::ORDER_STATUS_CONFIRMED => '已确认',
        self::ORDER_STATUS_CANCELED => '已取消',
        self::ORDER_STATUS_INVALID => '已作废',
    ];

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

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
            [['order_sn'], 'required'],
            [['order_status', 'pay_at', 'shipping_at', 'confirm_at', 'address_id', 'is_recommend', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['country', 'province', 'city', 'district', 'town',], 'integer'],
            [['zipcode'], 'string', 'max' => 6],
            [['address'], 'string', 'max' => 255],
            [['order_amount', 'goods_amount', 'coupon_amount'], 'number'],
            [['order_sn', 'pay_code'], 'string', 'max' => 20],
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
            'order_amount' => I18NUitl::t('app', '{Order}{Amount}'),
            'goods_amount' => I18NUitl::t('app', '{Goods}{Amount}'),
            'coupon_amount' => I18NUitl::t('app', '{Coupon}{Amount}'),
            'order_status' => I18NUitl::t('app', '{Order}{Status}'),
            'user_note' => Yii::t('app', 'User Note'),
            'pay_code' => Yii::t('app', 'Pay Code'),
            'pay_sn' => Yii::t('app', 'Pay Sn'),
            'pay_at' => Yii::t('app', 'Pay At'),
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
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
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
     * @param boolean $bo 支付成功
     * @param array $message
     */
    public function pay($bo, $message)
    {
        $tran = Yii::$app->db->beginTransaction();
        $order = $this;
        try {
            // 用户是否支付成功
            if ($bo) {
                $order->pay_code = 'weixin';
                $order->pay_sn = $message['transaction_id'];
                $order->pay_at = time(); // 更新支付时间为当前时间
                $order->order_status = Order::ORDER_STATUS_WAIT_DELIVER;
                $order->save(); // 保存订单
                OrderAction::saveLog([$order->id], '支付成功', "支付方式：{$order->pay_code}");
                // 设置购买的商品为待上图状态
                OrderGoods::updateAll(['status' => OrderGoods::STATUS_UPLOAD_PIC, 'init_at' => time()], ['order_id' => $order->id]);
                GrouponRecord::updateAll(['status' => GrouponRecord::STATUS_SUCCESS], ['order_id' => $order->id]);
            } else {
                // 用户支付失败
                $order->order_status = Order::ORDER_STATUS_PAY_FAIL;
                $order->save(); // 保存订单
                OrderAction::saveLog([$order->id], '支付失败', "{$message['err_code']}\n{$message['err_code_des']}");
            }

            $tran->commit();

            if ($bo) {
                // 清除临时订单
                GetTempOrder::clearTempOrder(Yii::$app->user->id, $order->orderGoods[0]->id);
                // 检查推荐人
                $this->checkRecommend();
            }

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
            return "订单无法确认！当前状态为：" . self::$orderStatusNameMap[$this->order_status];
        }
        $time = time();

        $tran = \Yii::$app->db->beginTransaction();
        try {
            //订单
            $this->order_status = self::ORDER_STATUS_CONFIRMED;
            $this->confirm_at = $time;
            $this->ar_save($this);
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
     * 分成
     */
    public function checkRecommend()
    {
        if ($this->order_status != self::ORDER_STATUS_WAIT_DELIVER) {
            return;
        }
        //是否为推荐
        $is_recommend = $this->is_recommend;
        /* @var $referrer User */
        $referrer = $is_recommend ? User::findOne(['id' => $this->recommend_by]) : null;

        //检查推荐人金额是否正常
        if ($is_recommend && !$referrer->moneyVerification()) {
            //账号余额不对，终止结算
            //...添加日志记录
            OrderAction::saveLog([$this->id], '确认失败', "订单确认失败，发现账号余额不对，账号：{$referrer->id}");
            return;
        }

        $tran = \Yii::$app->db->beginTransaction();
        try {
            //推荐
            if ($is_recommend && !OrderRecommend::findOne(['order_id' => $this->id])) {
                $commission = Config::getValue('order_recommend_commission');
                $order_recommend = new OrderRecommend([
                    'order_id' => $this->id,
                    'order_sn' => $this->order_sn,
                    'order_amount' => $this->order_amount,
                    'goods_name' => $this->orderGoods[0]->goods_name,
                    'commission' => $commission,
                    'amount' => floor(($commission > 1 ? $commission : $this->order_amount * $commission) * 100) / 100,
                    'recommend_by' => $this->recommend_by,
                    'created_by' => $this->created_by,
                ]);
                $this->ar_save($order_recommend);

                // 收入
                $money = floor(($referrer->money + $order_recommend->amount) * 100) / 100;
                $referrer->money = $money;
                $referrer->money_sign = $referrer->makeVerification($referrer->id, $money);
                $this->ar_save($referrer);

                $wallet_log = new WalletLog([
                    'user_id' => $referrer->id,
                    'type' => WalletLog::TYPE_INCOME,
                    'tran_sn' => $order_recommend->id . '',
                    'tran_money' => $order_recommend->amount,
                    'money_newest' => $referrer->money,
                    'des' => '推荐奖励',
                ]);
                $this->ar_save($wallet_log);

                //支出
                /*$model = new Withdrawals([
                    'user_id' => $referrer->id,
                    'order_sn' => Withdrawals::getRandomSN(),
                    'amount' => $order_recommend->amount,
                    'need_check' => 0,
                    'pay_account' => $referrer->auths->identifier,
                    'pay_realname' => $referrer->nickname,
                    'check_at' => time(),
                    'check_feedback' => '系统自动审核通过！10分钟内到账！',
                    'status' => Withdrawals::STATUS_CHECK_SUCCESS,
                ]);

                $this->ar_save($model);*/
            }

            $tran->commit();
        } catch (\Exception $ex) {
            $tran->rollBack();
            OrderAction::saveLog([$this->id], '收取拥金失败！', $ex->getMessage());
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
     * @return ActiveQuery
     */
    public function getCreater()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * 推荐人
     * @return ActiveQuery
     */
    public function getReferrer()
    {
        return $this->hasOne(User::class, ['id' => 'recommend_by']);
    }

    /**
     * 制作日志
     * @return ActiveQuery
     */
    public function getActionLogs()
    {
        return $this->hasMany(OrderAction::class, ['order_id' => 'id']);
    }

    /**
     * 绘本
     * @return ActiveQuery
     */
    public function getOrderGoods()
    {
        return $this->hasMany(OrderGoods::class, ['order_id' => 'id']);
    }

    /**
     * 创建订单
     *
     * @param Goods $goods 商品
     * @param GoodsSpecPrice $spec_price 规格
     * @param int $goods_num 商品数量
     * @param int $address_id 地址
     * @param array $params 可选参数
     *
     * @return array [Order,OrderGoods]
     *
     * @throws \yii\base\Exception
     */
    public static function createOrder($goods, $spec_price, $goods_num, $address_id, $params = [])
    {
        $user_id = Yii::$app->user->id;
        $groupon_id = $params['groupon_id'];
        $recommend_by = $params['recommend_by'];
        $user_note = $params['user_note'];
        $address = UserAddress::findOne(['id' => $address_id]);
        $goods_amount = $spec_price->goods_price * $goods_num; //商品总价
        /** @var $coupon UserCoupon */
        $coupon = $params['coupon'];
        // 优惠金额
        $coupon_amount = $coupon ?
            ($coupon->coupon->used_amount < 1 ? (1 - $coupon->coupon->used_amount) * $goods_amount : $coupon->coupon->used_amount) : 0;
        // 订单应付金额
        $order_amount = round(($goods_amount - $coupon_amount)*100)/100;
        if($order_amount <= 0){
            throw new \yii\base\Exception('定单金额异常');
        }

        $order = new Order([
            'order_sn' => self::getRandomSN(),
            'goods_amount' => $goods_amount,
            'order_amount' => $order_amount, //订单总额 = 商品总价 - 折扣
            'coupon_amount' => $coupon_amount,
            //推荐
            'is_recommend' => $recommend_by != null ? 1 : 0, //是否为推荐订单
            'recommend_by' => $recommend_by, //推挤人ID
            //收货地址
            'address_id' => $address->id, //地址ID
            'user_note' => $user_note, //留言
            'consignee' => $address->consignee, //收货人
            'zipcode' => $address->zipcode,
            'phone' => $address->phone,
            'province' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'town' => $address->town,
            'address' => $address->address,
            'created_by' => $user_id,
        ]);

        if ($order->save()) {
            /* 创建商品 */
            $order_goods = new OrderGoods([
                'order_id' => $order->id,
                'order_sn' => $order->order_sn,
                'created_by' => $user_id,
                'groupon_id' => $groupon_id,
                'type' => $goods->type,
                //商品
                'goods_id' => $goods->id,
                'goods_name' => $goods->goods_name, //商品名
                'goods_img' => $goods->cover_url, //图片
                'goods_price' => $spec_price->goods_price, //商品价格
                'goods_num' => $goods_num, //购买数量
                'goods_params' => $goods->params, //商品参数
                'scene_num' => $spec_price->scene_num, //购买场景数量
                'spec_id' => $spec_price->id, //价格ID
                'spec_key' => $spec_price->spec_key, //价格项ID
                'spec_key_name' => $spec_price->spec_key_name, //价格项名
                'amount' => $spec_price->goods_price * $goods_num, //总价
            ]);
            if (!$order_goods->save()) {
                throw new \yii\base\Exception(implode(',', $order_goods->getErrorSummary(true)));
            }

            // 创建记录日志
            OrderAction::saveLog([$order->id], '创建订单', '');
            OrderGoodsAction::saveLog([$order_goods->id], '订单创建', '');
            OrderGoodsAction::saveLog([$order_goods->id], '初始绘本', '用户初始化绘本');
        } else {
            throw new \yii\base\Exception(implode(',', $order->getErrorSummary(true)));
        }
        return [$order, $order_goods];
    }


    /**
     * 返回销售统计
     *
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     */
    public static function getSaleStat($start_time, $end_time)
    {
        $query = (new Query())->from(self::tableName())
            ->select(['count(1) sale_count', 'IFNULL(sum(order_amount),0) turnover'])
            ->where(['between', 'created_at', $start_time, $end_time])
            ->andWhere(['order_status' => self::ORDER_STATUS_WAIT_DELIVER]);
        $data = $query->one();
        $data['turnover'] = round((int)$data['turnover'] * 100) / 100;
        return $data;
    }

    /**
     * 返回所有绘本销售统计
     *
     * @param int $day_num
     */
    public static function getSaleStatByGoods($day_num = 7)
    {
        $data = (new Query())
            ->select([
                'OrderGoods.goods_id',
                'OrderGoods.goods_name',
                'IFNULL(sum(Order.order_amount),0) turnover',
                'COUNT(1) count',
                new Expression('FROM_UNIXTIME(Order.created_at,\'%Y-%m-%d\') as date'),
            ])
            ->from(['Order' => self::tableName()])
            ->leftJoin(['OrderGoods' => OrderGoods::tableName()], 'OrderGoods.order_id = Order.id')
            ->leftJoin(['Goods' => Goods::tableName()], 'Goods.id = OrderGoods.goods_id')
            ->andWhere(['between', 'Order.created_at', strtotime("today -$day_num day 00:00:00"), time()])
            ->andWhere(['Order.order_status' => self::ORDER_STATUS_WAIT_DELIVER])
            ->groupBy(['OrderGoods.goods_id', 'date'])
            ->all();

        return $data;
    }

    /**
     * 获取前一段时间的销售记录
     * @param int $agencyId
     * @param int $day_num 前几天，默认30天
     */
    public static function getDailySaleLog($day_num = 30)
    {
        //查出指定范围销售记录
        $daily_sale_logs = self::find()
            ->select([
                'IFNULL(sum(order_amount),0) turnover',
                'COUNT(1) count',
                new Expression('FROM_UNIXTIME(created_at,\'%Y-%m-%d\') as date'),
            ])
            ->andWhere(['between', 'created_at', strtotime("today -$day_num day 00:00:00"), time()])
            ->andWhere(['order_status' => self::ORDER_STATUS_WAIT_DELIVER])
            ->groupBy(['date'])
            ->asArray()
            ->all();

        return $daily_sale_logs;
    }

}
