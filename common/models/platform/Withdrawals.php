<?php

namespace common\models\platform;

use common\components\redis\RedisService;
use common\models\User;
use common\utils\I18NUitl;
use EasyWeChat\Payment\Application;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%withdrawals}}".
 *
 * @property string $id
 * @property string $user_id 用户ID,关联user
 * @property string $order_sn 提现订单ID
 * @property string $amount 金额
 * @property int $need_check 是否需要审核：0否 1是
 * @property string $check_user_id 审核人ID，关联admin_user,id
 * @property string $check_at 审核时间
 * @property string $check_feedback 审核反馈
 * @property string $tran_sn 交易流水号,分别为收益和提现记录id
 * @property string $pay_account 收款账号
 * @property string $pay_realname 收款真实姓名
 * @property string $pay_code 收款机构（weixin、alipay、bank）
 * @property string $pay_at 付款时间
 * @property string $pay_error_code
 * @property int $status 状态：0申请中 1审核失败 5付款失败 6付款成功
 * @property string $des 备注
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * 
 * @property User $user 提现人
 * @property User $checker 审核人
 */
class Withdrawals extends ActiveRecord
{

    const STATUS_APPLYING = 0; //申请中
    const STATUS_CHECK_FAILED = 1; //审核失败
    const STATUS_CHECK_SUCCESS = 2; //审核成功
    const STATUS_PAY_FAILED = 5; //付款失败
    const STATUS_PAY_SUCCESS = 6; //付款成功

    static $statusNameMap = [
        self::STATUS_APPLYING => '申请中',
        self::STATUS_CHECK_FAILED => '审核失败',
        self::STATUS_CHECK_SUCCESS => '审核成功',
        self::STATUS_PAY_FAILED => '付款失败',
        self::STATUS_PAY_SUCCESS => '付款成功',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%withdrawals}}';
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
            [['user_id'], 'required'],
            [['user_id', 'need_check', 'check_user_id', 'check_at', 'pay_at', 'status', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['order_sn', 'tran_sn'], 'string', 'max' => 20],
            [['check_feedback', 'pay_error_code', 'des'], 'string', 'max' => 255],
            [['pay_account', 'pay_realname', 'pay_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'order_sn' => Yii::t('app', 'Order Sn'),
            'amount' => Yii::t('app', 'Amount'),
            'need_check' => Yii::t('app', 'Need Check'),
            'check_user_id' => Yii::t('app', 'Check User ID'),
            'check_at' => Yii::t('app', 'Check At'),
            'check_feedback' => I18NUitl::t('app', '{Check}{Feedback}'),
            'tran_sn' => Yii::t('app', 'Tran Sn'),
            'pay_account' => Yii::t('app', 'Pay Account'),
            'pay_realname' => Yii::t('app', 'Pay Realname'),
            'pay_code' => Yii::t('app', 'Pay Code'),
            'pay_at' => Yii::t('app', 'Pay At'),
            'pay_error_code' => Yii::t('app', 'Pay Error Code'),
            'status' => Yii::t('app', 'Status'),
            'des' => Yii::t('app', 'Des'),
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
        $key = "Withdrawals:RandomSN:$key_sn";
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
     * 付款提现
     */
    public function pay()
    {
        /* @var $user User */
        $user = User::findOne(['id' => $this->user_id]);
        $this->pay_at = time();
        if (!$user->moneyVerification()) {
            $this->status = self::STATUS_PAY_FAILED;
            $this->pay_error_code = '用户金额校检失败！';
            $this->save();
        } else {
            $newMoney = floatval($user->money) - floatval($this->amount);
            $newMoney = floor($newMoney * 10000)/10000;
            if ($newMoney < 0) {
                $this->status = self::STATUS_PAY_FAILED;
                $this->pay_error_code = '提现金额超出余额！';
                $this->save();
            } else {
                /* @var $payment Application */
                  $payment = Yii::$app->wechat->payment;

                  $message = $payment->transfer->toBalance([
                  'partner_trade_no' => $this->order_sn, // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
                  'openid' => $this->pay_account,
                  'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
                  're_user_name' => $this->pay_realname, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
                  'amount' => $this->amount * 100, // 企业付款金额，单位为分
                  'desc' => "用户提现", // 企业付款操作说明信息。必填
                  ]);

                //$message = $this->test();

                $tran = Yii::$app->db->beginTransaction();
                try {
                    if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                        // 付款用户是否支付成功
                        if ($message['result_code'] === 'SUCCESS') {
                            //设置订单已支付
                            $this->pay_code = 'weixin';
                            $this->tran_sn = $message['payment_no'];
                            $this->status = self::STATUS_PAY_SUCCESS;
                            if (!$this->save()) {
                                throw new Exception(implode(',', $this->getErrorSummary(true)));
                            }

                            //修改用户最新金额
                            $user->money = $newMoney;
                            $user->money_sign = $user->makeVerification($user->id, $newMoney);
                            if (!$user->save()) {
                                throw new Exception(implode(',', $user->getErrorSummary(true)));
                            }

                            //添加用户流水日志
                            $wallet_log = new WalletLog([
                                'user_id' => $user->id,
                                'type' => WalletLog::TYPE_WITHDRAW,
                                'tran_sn' => "$this->id",
                                'tran_money' => $this->amount,
                                'money_newest' => $newMoney,
                                'des' => '用户提现',
                            ]);
                            if (!$wallet_log->save()) {
                                throw new Exception(implode(',', $wallet_log->getErrorSummary(true)));
                            }
                        } else if ($message['result_code'] === 'FAIL' && $message['err_code'] !== 'SYSTEMERROR') {
                            // 用户支付失败
                            $this->status = self::STATUS_PAY_FAILED;
                            $this->pay_error_code = "{$message['err_code']}:{$message['return_msg']}";
                            if (!$this->save()) {
                                throw new Exception(implode(',', $this->getErrorSummary(true)));
                            }
                        }
                    } else {
                        //return $fail('通信失败，请稍后再通知我');
                        \Yii::error("企业付款失败,ID：{$this->id},原因：{$message['return_msg']}");
                    }

                    $tran->commit();
                    return true; // 返回处理完成
                } catch (Exception $ex) {
                    $tran->rollBack();
                    //return $fail('通信失败，请稍后再通知我');
                    \Yii::error("企业付款失败,ID：{$this->id},原因：{$ex->getMessage()}");
                }
            }
        }
    }

    private function test()
    {
        $success = rand(1, 10);

        if ($success > 2) {
            $message = [
                'return_code' => 'SUCCESS',
                'result_code' => 'SUCCESS',
                'payment_no' => 'test' . time(),
            ];
        } else {
            $message = [
                'return_code' => 'SUCCESS',
                'result_code' => 'FAIL',
                'return_msg' => "失败原因：$success",
            ];
        }
        return $message;
    }

    /**
     * 返回详情信息
     */
    public function toDetail()
    {
        $model = $this;
        $creater = $model->creater;
        $roleClass = $creater->type == 2 ? Agency::class : Merchant::class;
        $role = $roleClass::findOne(['user_id' => $creater->id]);

        $item = $model->toArray();
        $item['status_text'] = Withdrawals::$statusNameMap[$model->status];
        $item['role_id'] = $role->id;
        $item['creater'] = $model->creater->nickname;
        $item['creater_type'] = $model->creater->type;
        $item['created_time'] = date('Y-m-d H:i:s', $model->created_at);
        $item['check_time'] = date('Y-m-d H:i:s', $model->check_at);
        $item['checker'] = empty($model->check_user_id) ? "系统" : $model->checker->nickname;
        $item['pay_time'] = date('Y-m-d H:i:s', $model->pay_at);
        switch ($model->status) {
            case Withdrawals::STATUS_APPLYING:
                $item['feedback'] = '等待审核！';
                break;
            case Withdrawals::STATUS_CHECK_FAILED:
                $item['feedback'] = $model->check_feedback;
                break;
            case Withdrawals::STATUS_CHECK_SUCCESS:
                $item['feedback'] = '提现申请已审核通过！';
                break;
            case Withdrawals::STATUS_PAY_FAILED:
                $item['feedback'] = $model->pay_error_code;
                break;
            case Withdrawals::STATUS_PAY_SUCCESS:
                $item['feedback'] = "{$item['pay_time']} 提现成功！";
                break;
        }
        return $item;
    }

    /**
     * 
     * @return QueryeRecord
     */
    public function getChecker()
    {
        return $this->hasOne(User::class, ['id' => 'check_user_id']);
    }

    /**
     * 
     * @return QueryeRecord
     */
    public function getCreater()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
