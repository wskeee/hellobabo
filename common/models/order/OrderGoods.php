<?php

namespace common\models\order;

use common\models\goods\Goods;
use common\models\User;
use common\utils\I18NUitl;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order_goods}}".
 *
 * @property int $id
 * @property int $order_id 订单ID，关联order表id字段
 * @property int $type 类型 0默认 1赠送 2团购
 * @property int $groupon_id 团购ID，关联groupon,id
 * @property string $order_sn 订单编号
 * @property int $goods_id  商品（媒体）ID，关联media表id字段
 * @property string $goods_name 订单名称
 * @property string $goods_img 商品图片地址
 * @property string $goods_price 商品价格
 * @property string $goods_cost 商品成本
 * @property int $goods_num 购买数
 * @property string $goods_params 附加参数
 * @property string $user_cover_url 用户封面路径
 * @property string $share_thumb_url 用户缩略分享图
 * @property string $user_poster_url 用户海报图片
 * @property int $spec_id 规格id
 * @property string $spec_key 商品规格key
 * @property string $spec_key_name 规格对应的中文名字
 * @property int $scene_num 场景数
 * @property string $amount 总价
 * @property int $status 状态
 * @property int $init_at 初始时间
 * @property int $upload_finish_at 上图时间
 * @property int $design_at 设计时间
 * @property int $print_at 印刷时间
 * @property int $is_del 是否已删除 0否 1是
 * @property int $created_by 创建人（购买人），关联user表id字段
 * @property int $created_at 创建时间（购买时间）
 * @property int $updated_at 更新时间
 *
 * @property Order $order 订单
 * @property Goods $goods 商品
 * @property Groupon $groupon
 * @property User $creater 创建人
 * @property OrderGoodsMaterial[] $orderGoodsMaterials 订单素材
 * @property OrderGoodsScene[] $orderGoodsScenes 订单场景
 * @property OrderGoodsScenePage[] $orderGoodsScenePages 订单场景页
 * @property OrderGoodsAction[] $actionLogs 日志记录
 * @property OrderGoodsComment[] $comments 留言
 *
 */
class OrderGoods extends ActiveRecord
{
    /* 制作状态 */

    const STATUS_UNREADY = 0;            //未准备
    const STATUS_INIT = 1;               //初始
    const STATUS_UPLOAD_PIC = 2;         //上图
    const STATUS_UPLOAD_PIC_CHECK = 3;   //上图审核
    const STATUS_UPLOAD_PIC_CHECK_FAIL = 4;   //上图审核不通过
    const STATUS_WAIT_DESIGN = 5;        //待设计
    const STATUS_DESIGNING = 6;          //设计中
    const STATUS_DESIGN_CHECK = 7;       //设计审核
    const STATUS_DESIGN_CHECK_FAIL = 8;  //设计审核不通过
    const STATUS_WAIT_PRINT = 10;        //待印刷
    const STATUS_PRINTING = 11;          //印刷中
    const STATUS_PRINT_CHECK = 12;       //印刷审核
    const STATUS_PRINT_CHECK_FAIL = 13;  //印刷审核不通过
    const STATUS_FINISH = 20;            //已完成
    const STATUS_INVALID = 99;           //已作废

    /* 制作状态名 */

    public static $statusNameMap = [
        self::STATUS_UNREADY => '未准备',
        self::STATUS_INIT => '初始',
        self::STATUS_UPLOAD_PIC => '上图',
        self::STATUS_UPLOAD_PIC_CHECK => '上图审核',
        self::STATUS_UPLOAD_PIC_CHECK_FAIL => '上图审核不通过',
        self::STATUS_WAIT_DESIGN => '待设计',
        self::STATUS_DESIGN_CHECK => '设计审核',
        self::STATUS_DESIGN_CHECK_FAIL => '设计审核不通过',
        self::STATUS_DESIGNING => '设计中',
        self::STATUS_WAIT_PRINT => '待印刷',
        self::STATUS_PRINTING => '印刷中',
        self::STATUS_PRINT_CHECK => '印刷审核',
        self::STATUS_PRINT_CHECK_FAIL => '印刷审核不通过',
        self::STATUS_FINISH => '已完成',
        self::STATUS_INVALID => '已作废',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods}}';
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
            [['order_id', 'goods_id', 'created_by'], 'required'],
            [['order_id', 'goods_id', 'groupon_id', 'goods_num', 'spec_id', 'type', 'scene_num', 'status', 'init_at', 'upload_finish_at', 'design_at', 'print_at', 'is_del', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['goods_price', 'goods_cost', 'amount'], 'number'],
            [['order_sn'], 'string', 'max' => 20],
            [['goods_name', 'spec_key', 'spec_key_name'], 'string', 'max' => 100],
            [['goods_img', 'user_cover_url', 'share_thumb_url', 'user_poster_url'], 'string', 'max' => 255],
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
            'order_id' => Yii::t('app', 'Order'),
            'groupon_id' => Yii::t('app', 'Groupon'),
            'order_sn' => Yii::t('app', 'Sn'),
            'type' => Yii::t('app', 'Type'),
            'goods_id' => Yii::t('app', 'Goods'),
            'goods_name' => Yii::t('app', 'Name'),
            'goods_img' => Yii::t('app', 'Cover'),
            'goods_price' => Yii::t('app', 'Price'),
            'goods_cost' => Yii::t('app', 'Cost'),
            'goods_num' => Yii::t('app', 'Num'),
            'user_cover_url' => I18NUitl::t('app', '{User}{Cover}'),
            'share_thumb_url' => I18NUitl::t('app', '{Share}{Thumb}'),
            'user_poster_url' => I18NUitl::t('app', '{User}{Poster}'),
            'spec_id' => Yii::t('app', 'Spec ID'),
            'spec_key' => Yii::t('app', 'Spec Key'),
            'spec_key_name' => Yii::t('app', 'Spec'),
            'scene_num' => Yii::t('app', 'Scene Num'),
            'goods_params' => Yii::t('app', 'Params'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'init_at' => Yii::t('app', 'Init At'),
            'upload_finish_at' => Yii::t('app', 'Upload At'),
            'design_at' => Yii::t('app', 'Design At'),
            'print_at' => Yii::t('app', 'Print At'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 订单
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * 商品
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::class, ['id' => 'goods_id']);
    }

    /**
     * 团购
     * @return ActiveQuery
     */
    public function getGroupon()
    {
        return $this->hasOne(Groupon::class, ['id' => 'groupon_id']);
    }

    /**
     * 订单素材
     * @return ActiveQuery
     */
    public function getOrderGoodsMaterials()
    {
        return $this->hasMany(OrderGoodsMaterial::class, ['order_goods_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * 订单场景
     * @return ActiveQuery
     */
    public function getOrderGoodsScenes()
    {
        return $this->hasMany(OrderGoodsScene::class, ['order_goods_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * 订单场景页
     * @return ActiveQuery
     */
    public function getOrderGoodsScenePages()
    {
        return $this->hasMany(OrderGoodsScenePage::class, ['order_goods_id' => 'id'])->where(['is_del' => 0])->with('scene');
    }

    /**
     * 制作日志
     * @return ActiveQuery
     */
    public function getActionLogs()
    {
        return $this->hasMany(OrderGoodsAction::class, ['order_goods_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * 制作日志
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(OrderGoodsComment::class, ['order_goods_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreater()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $arr = parent::toArray($fields, $expand, $recursive);
        $arr['create_time'] = date('Y.m.d H:i:s', $this->created_at);
        $arr['creater_name'] = $this->creater->nickname;
        return $arr;
    }

}
