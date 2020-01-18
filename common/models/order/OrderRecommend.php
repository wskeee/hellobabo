<?php

namespace common\models\order;

use common\models\User;
use phpDocumentor\Reflection\Types\Self_;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "{{%order_recommend}}".
 *
 * @property int $id
 * @property int $order_id 订单id，关联order表id字段
 * @property string $order_sn 订单编号,关联order,order_sn
 * @property string $order_amount 订单总额
 * @property string $goods_name 商品名称
 * @property number $commission 佣金 小于1等于订单金额百份比，大于1等于实际金额
 * @property string $amount 参与奖励金额
 * @property int $recommend_by 推荐人id,关联admin_user表id字段
 * @property int $created_by 创建人（购买人）ID,关联user,id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * 
 * @property User $recommender 推荐人
 * @property User $creater 购买人
 */
class OrderRecommend extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_recommend}}';
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
            [['order_id', 'created_by'], 'required'],
            [['order_id', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_amount', 'amount','commission'], 'number'],
            [['order_sn'], 'string', 'max' => 20],
            [['goods_name'], 'string', 'max' => 255],
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
            'order_amount' => Yii::t('app', 'Order Amount'),
            'goods_name' => Yii::t('app', 'Goods Name'),
            'commission' => Yii::t('app', 'Commission'),
            'amount' => Yii::t('app', 'Amount'),
            'recommend_by' => Yii::t('app', 'Recommend By'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * @return QueryRecord
     */
    public function getRecommender(){
        return $this->hasOne(User::class, ['id' => 'recommend_by']);
    }
    
    /**
     * @return QueryRecord
     */
    public function getCreater(){
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * 获取推荐数据
     *
     * @param array $where 条件
     */
    public static function getPromotionExpensesStat($where = null)
    {
        $query = new Query();
        $query->from(self::tableName());
        $query->select(['count(1) count','IFNULL(sum(amount),0) amount']);
        if($where){
            $query->where($where);
        }
        $data = $query->all();

        return $data[0];
    }
}
