<?php

namespace common\models\shop;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%shop_sale_record}}".
 *
 * @property int $id
 * @property int $shop_id 商店ID,关联shop.id
 * @property int $order_id 订单ID,关联order.id
 * @property string $income_value 收益设置
 * @property string $real_income 真实收益
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ShopSaleRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_sale_record}}';
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
            [['shop_id', 'order_id', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['income_value', 'real_income'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_id' => Yii::t('app', 'Shop ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'income_value' => Yii::t('app', 'Income Value'),
            'real_income' => Yii::t('app', 'Real Income'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
