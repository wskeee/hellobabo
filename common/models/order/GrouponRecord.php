<?php

namespace common\models\order;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "{{%groupon_record}}".
 *
 * @property int $id
 * @property int $user_id 用户ID，关联User,id
 * @property int $groupon_id 团购ID，关联groupon,id
 * @property int $order_id 订单ID，关联order,id
 * @property int $order_goods_id 订单商品ID，关联order_goods,id
 * @property int $status 状态 1加入中 2成功加入 99加入失败
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class GrouponRecord extends ActiveRecord
{
    /* 加入中 */

    const STATUS_JOINING = 1;
    /* 成功 */
    const STATUS_SUCCESS = 2;
    /* 无效 */
    const STATUS_INVALID = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%groupon_record}}';
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
            [['user_id', 'groupon_id', 'order_id', 'order_goods_id', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'groupon_id' => Yii::t('app', 'Groupon ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'order_goods_id' => Yii::t('app', 'Order Goods ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 获取团购记录
     * @param array|string $grouop_ids
     */
    public static function getGrouponRecordDetail($grouop_ids)
    {
        $groupon_records_result = (new Query())
                        ->select(['GrouponRecord.*', 'User.nickname user_name', 'User.avatar', 'OrderGoods.status as goods_status'])
                        ->from(['GrouponRecord' => GrouponRecord::tableName()])
                        ->leftJoin(['OrderGoods' => OrderGoods::tableName()], 'GrouponRecord.order_goods_id = OrderGoods.id')
                        ->leftJoin(['User' => User::tableName()], 'GrouponRecord.user_id = User.id')
                        ->where([
                            'GrouponRecord.groupon_id' => $grouop_ids,
                            'GrouponRecord.status' => [GrouponRecord::STATUS_JOINING, GrouponRecord::STATUS_SUCCESS]]
                        )
                        ->all();
        foreach ($groupon_records_result as &$record) {
            $record['status_text'] = $record['status'] == GrouponRecord::STATUS_JOINING ? "加入中" : (
                    $record['goods_status'] == OrderGoods::STATUS_INIT ? "初始绘本" : ($record['goods_status'] < OrderGoods::STATUS_WAIT_DESIGN ? "上传相片" : "已经准备"));
        }
        return $groupon_records_result;
    }

}
