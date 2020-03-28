<?php

namespace common\models\order;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%groupon}}".
 *
 * @property int $id 名称
 * @property string $name
 * @property int $type 类型 1默认
 * @property string $des 描述
 * @property string $cover_url 封面路径
 * @property int $order_id 订单ID，关联order表id字段
 * @property int $order_goods_id 订单商品ID，关联order_goods,id
 * @property int $goods_id 商品ID，关联goods,id
 * @property string $goods_name 商品名称
 * @property string $goods_img 商品图片地址
 * @property string $goods_params 商品参数
 * @property int $spec_id 规格id
 * @property string $spec_key 商品规格key
 * @property string $spec_key_name 规格对应的中文名字
 * @property string $amount 总价
 * @property int $status 状态 1正常 2已完成 99作废
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $created_by 创建人（购买人），关联user表id字段
 * @property int $created_at 创建时间（购买时间）
 * @property int $updated_at 更新时间
 */
class Groupon extends ActiveRecord
{

    // 行动中
    const STATUS_RUNING = 1;
    // 已完成
    const STATUS_FINISHED = 5;
    // 作废
    const STATUS_INVALID = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%groupon}}';
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
            [['name', 'created_by'], 'required'],
            [['type', 'order_id', 'order_goods_id', 'goods_id', 'spec_id', 'status', 'start_time', 'end_time', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['des', 'cover_url', 'goods_img'], 'string', 'max' => 255],
            [['goods_name', 'spec_key', 'spec_key_name'], 'string', 'max' => 100],
            [['goods_params'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'des' => Yii::t('app', 'Des'),
            'cover_url' => Yii::t('app', 'Cover Url'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'goods_name' => Yii::t('app', 'Goods Name'),
            'goods_img' => Yii::t('app', 'Goods Img'),
            'goods_params' => Yii::t('app', 'Params'),
            'spec_id' => Yii::t('app', 'Spec ID'),
            'spec_key' => Yii::t('app', 'Spec Key'),
            'spec_key_name' => Yii::t('app', 'Spec Key Name'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 查询相关团购
     *
     * @param int $groupon_id
     * @return Groupon
     * @throws Exception
     */
    public static function findGroupon($groupon_id)
    {
        if ($groupon_id != null) {
            $groupon = Groupon::findOne(['id' => $groupon_id, 'status' => Groupon::STATUS_RUNING]);
            if ($groupon == null) {
                throw new Exception('团购不存在！');
            } else if ($groupon->status == Groupon::STATUS_FINISHED) {
                throw new Exception('来晚啦，该团购已满！');
            }
            return $groupon;
        }
        return null;
    }

    /**
     * 创建团购
     *
     * @param string $name 团购名称
     * @param string $des 团购描述
     * @param string $cover_url 团购封面
     * @param int $amount 团购总金额
     * @param OrderGoods $orderGoods 团购商品
     *
     * @throws Exception
     */
    public static function createGroupon($name, $des, $cover_url, $amount, $order_goods)
    {
        $groupon = new Groupon([
            'name' => $name,
            'des' => $des,
            'cover_url' => $cover_url,
            'order_id' => $order_goods->order_id,
            'order_goods_id' => $order_goods->id,
            'goods_id' => $order_goods->goods_id,
            'goods_name' => $order_goods->goods_name,
            'goods_img' => $order_goods->goods_img,
            'goods_params' => $order_goods->goods_params,
            'spec_id' => $order_goods->spec_id,
            'spec_key' => $order_goods->spec_key,
            'spec_key_name' => $order_goods->spec_key_name,
            'amount' => $amount,
            'created_by' => Yii::$app->user->id,
        ]);
        if ($groupon->validate() && $groupon->save()) {
            $order_goods->groupon_id = $groupon->id;
            $order_goods->save();
            $record = new GrouponRecord([
                'groupon_id' => $groupon->id,
                'user_id' => Yii::$app->user->id,
                'order_id' => $order_goods->order_id,
                'order_goods_id' => $order_goods->id,
                'status' => GrouponRecord::STATUS_SUCCESS,
            ]);
            $record->save();
        } else {
            throw new Exception(implode(',', $groupon->getErrorSummary(true)));
        }
    }

    /**
     * 加入团购
     *
     * @param Groupon $groupon 团购
     * @param int $user_id 用户ID
     * @param int $goods_id 商品ID
     * @param int $order_goods_id 订单商品ID
     */
    public static function joinGroupon($groupon, $user_id, $order_id, $order_goods_id)
    {
        $groupon_id = $groupon->id;
        $groupon_record = new GrouponRecord([
            'user_id' => $user_id,
            'groupon_id' => $groupon_id,
            'order_id' => $order_id,
            'order_goods_id' => $order_goods_id,
        ]);
        $groupon_record->save();

        // 检查是否满员
        $role_num = json_decode($groupon->goods_params)->role_num;
        $count = GrouponRecord::find()->where(['groupon_id' => $groupon_id])->andWhere(['<>', 'status', GrouponRecord::STATUS_INVALID])->count();
        if ($count >= $role_num) {
            $groupon->status = Groupon::STATUS_FINISHED;
            $groupon->save();
        }
    }

}
