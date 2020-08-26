<?php

namespace common\models\shop;

use common\models\AdminUser;
use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shop}}".
 *
 * @property int $id
 * @property int $user_id 用户ID,关联admin_user.id
 * @property string $name 名称
 * @property string $logo_url logo地址
 * @property string $income_value 收益 小于1百份比 大于1
 * @property string $des 描述
 * @property int $status 状态 0未准备 1启用 2停用
 * @property string $cover_url 封面地址
 * @property int $goods_count 商品数
 * @property int $order_count 订单数
 * @property number $all_income 历史收入
 * @property number $real_income 实际收入
 * @property number $day_income 当日收入
 * @property number $month_income 当月收入
 * @property int $last_income_time 最后一次收入时间
 *
 * @property AdminUser $user
 */
class Shop extends ActiveRecord
{
    const STATUS_UNREADY = 0;
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    public static $statusNames = [
        self::STATUS_UNREADY => '未准备',
        self::STATUS_ENABLED => '启用',
        self::STATUS_DISABLED => '禁用',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'goods_count', 'order_count', 'last_income_time'], 'integer'],
            [['income_value', 'all_income', 'real_income', 'day_income', 'month_income'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['logo_url', 'des', 'cover_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Account'),
            'name' => Yii::t('app', 'Name'),
            'logo_url' => Yii::t('app', 'Logo'),
            'income_value' => Yii::t('app', 'Income Value'),
            'des' => Yii::t('app', 'Des'),
            'status' => Yii::t('app', 'Status'),
            'cover_url' => Yii::t('app', 'Cover'),
            'goods_count' => Yii::t('app', 'Goods Count'),
            'order_count' => Yii::t('app', 'Order Count'),
            'all_income' => I18NUitl::t('app', 'All Income'),
            'real_income' => I18NUitl::t('app', 'Real Income'),
            'day_income' => I18NUitl::t('app', 'Day Income'),
            'month_income' => I18NUitl::t('app', 'Month Income'),
            'last_income_time' => I18NUitl::t('app', 'Last Income Time'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'user_id']);
    }
}
