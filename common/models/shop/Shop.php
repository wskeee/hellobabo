<?php

namespace common\models\shop;

use Yii;

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
 */
class Shop extends \yii\db\ActiveRecord
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
            [['user_id', 'status', 'goods_count', 'order_count'], 'integer'],
            [['income_value'], 'number'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'logo_url' => Yii::t('app', 'Logo Url'),
            'income_value' => Yii::t('app', 'Income Value'),
            'des' => Yii::t('app', 'Des'),
            'status' => Yii::t('app', 'Status'),
            'cover_url' => Yii::t('app', 'Cover Url'),
            'goods_count' => Yii::t('app', 'Goods Count'),
            'order_count' => Yii::t('app', 'Order Count'),
        ];
    }
}
