<?php

namespace common\models\platform;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%wallet_log}}".
 *
 * @property string $id
 * @property string $user_id 用户ID,关联user
 * @property int $type 类型:1收益 5提现
 * @property string $tran_sn 交易流水号,分别为收益和提现记录id
 * @property string $tran_money 交易金额
 * @property string $money_newest 最新余额，交易后余额
 * @property string $des 备注
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class WalletLog extends ActiveRecord
{

    //收入
    const TYPE_INCOME = 1;
    //提现
    const TYPE_WITHDRAW = 5;

    /**
     * 流水类型
     * @var type 
     */
    public static $typeNameMap = [
        self::TYPE_INCOME => '收入',
        self::TYPE_WITHDRAW => '提现',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wallet_log}}';
    }
    
    public function behaviors() {
        return [TimestampBehavior::class];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['tran_money', 'money_newest'], 'number'],
            [['tran_sn'], 'string', 'max' => 20],
            [['des'], 'string', 'max' => 255],
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
            'type' => Yii::t('app', 'Type'),
            'tran_sn' => Yii::t('app', 'Tran Sn'),
            'tran_money' => Yii::t('app', 'Tran Money'),
            'money_newest' => Yii::t('app', 'Money Newest'),
            'des' => Yii::t('app', 'Des'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

}
