<?php

namespace common\models\goods;

use common\models\AdminUser;
use common\models\system\Issue;
use common\utils\I18NUitl;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property int $id
 * @property int $category_id 商品的所属类目ID，关联goods_category表id字段
 * @property int $model_id 模型ID,goods_model表,id
 * @property int $owner_id 当前所有者id，关联admin_user表id字段
 * @property string $goods_sn 商品编码
 * @property string $goods_name 商品名称
 * @property string $goods_english_name 商品英文名称
 * @property string $goods_title 商品标题
 * @property string $goods_title_url 商品标题路径
 * @property string $goods_cost 成本
 * @property string $goods_price 价格
 * @property string $goods_des 描述/简介
 * @property int $type 类型 0默认 1赠送 2团购
 * @property string $cover_url 封面路径
 * @property string $video_url 视频地址
 * @property string $show_urls 展示图片地址多个使用','分隔
 * @property string $poster_url 海报图路径
 * @property string $share_thumb_url 分享缩略图路径
 * @property int $status 普通状态 1待发布 2已发布 3已下架
 * @property string $tags 标签，多个使用逗号分隔
 * @property string $commission 拥金
 * @property string $params 附加参数
 * @property int $store_count 库存
 * @property int $comment_count 评论数
 * @property int $click_count 查看/击数
 * @property int $share_count 分享/转发次数
 * @property int $like_count 点赞数
 * @property int $sale_count 销售数量
 * @property int $created_by 创建人id，关联admin_user表id字段
 * @property int $updated_by 最后最新人id，关联admin_user表id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property AdminUser $owner   作者
 * @property AdminUser $creater   创建人
 * @property AdminUser $updater   更新人
 * @property GoodsCategory $goodsCategory   分类
 * @property GoodsModel $goodsModel         模型
 * @property GoodsFavorites $id0
 * @property GoodsRecord $id1
 * @property GoodsAction[] $goodsActions
 * @property GoodsApprove[] $goodsApproves
 * @property GoodsAttValueRef[] $goodsAttValueRefs
 * @property GoodsDetail $goodsDetails
 * @property GoodsTagRef[] $goodsTagRefs
 * @property Issue[] $issues
 * @property array[] $goodsSpecItems    所有商品规格
 */
class Goods extends ActiveRecord
{
    /* 未发布 */

    const STATUS_UNPUBLISHED = 1;
    /* 已发布 */
    const STATUS_PUBLISHED = 2;
    /* 已下架 */
    const STATUS_SOLD_OUT = 3;

    /* 类型  */
    /* 默认 */
    const TYPE_DEFAULT = 1;
    /* 赠送、礼物 */
    const TYPE_PRESENT = 2;
    /* 团购 */
    const TYPE_GROUPON = 3;

    /* 状态 */

    public static $statusKeyMap = [
        self::STATUS_UNPUBLISHED => '未发布',
        self::STATUS_PUBLISHED => '已发布',
        self::STATUS_SOLD_OUT => '已下架',
    ];

    /* 类型 */
    public static $typeKeyMap = [
        self::TYPE_DEFAULT => '默认',
        self::TYPE_PRESENT => '赠送',
        self::TYPE_GROUPON => '团购',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'owner_id', 'goods_name'], 'required'],
            [['category_id', 'model_id', 'owner_id', 'type', 'status', 'store_count', 'comment_count', 'click_count', 'share_count', 'like_count', 'sale_count', 'init_required', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['goods_cost', 'goods_price', 'commission'], 'number'],
            [['goods_sn'], 'string', 'max' => 20],
            [['goods_name'], 'string', 'max' => 100],
            [['goods_title'], 'string', 'max' => 50],
            [['show_urls'], 'arrTostr'],
            [['goods_des', 'cover_url', 'video_url', 'poster_url', 'share_thumb_url', 'tags','goods_english_name','goods_title_url'], 'string', 'max' => 255],
            [['params'], 'string'],
            [['tags'], 'tagVerify',],
        ];
    }

    /**
     * 数组转字符
     *
     * @param string|array $att
     * @return boolean
     */
    public function arrTostr($att)
    {
        $value = $this[$att];
        if (is_array($value)) {
            $value = implode(',', array_filter($value));
        }
        $this[$att] = $value;
        return true;
    }

    /**
     * 标签验证
     * @param type $attribute
     * @return boolean
     */
    public function tagVerify($attribute)
    {
        $tags = $this->tags;
        if (is_string($tags) && !empty($tags)) {
            //把全角",""、"替换为半角","
            $tags = str_replace(['，', '、'], ',', $tags);
            $this->tags = $tags;
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => I18NUitl::t('app', '{Goods}{SN}'),
            'category_id' => Yii::t('app', 'Category'),
            'model_id' => Yii::t('app', 'Model'),
            'owner_id' => Yii::t('app', 'Owner'),
            'type' => Yii::t('app', 'Type'),
            'commission' => Yii::t('app', 'Commission'),
            'goods_name' => Yii::t('app', 'Name'),
            'goods_english_name' => Yii::t('app', 'Goods English Name'),
            'goods_title' => I18NUitl::t('app', 'Title'),
            'goods_title_url' => I18NUitl::t('app', '{Title}{Url}'),
            'goods_cost' => Yii::t('app', 'Cost'),
            'goods_price' => Yii::t('app', 'Price'),
            'goods_des' => Yii::t('app', 'Des'),
            'cover_url' => Yii::t('app', 'Cover'),
            'video_url' => Yii::t('app', 'Video'),
            'show_urls' => Yii::t('app', 'Show Urls'),
            'poster_url' => Yii::t('app', 'Poster'),
            'share_thumb_url' => Yii::t('app', 'Share Thumb'),
            'status' => Yii::t('app', 'Status'),
            'tags' => Yii::t('app', 'Tag'),
            'params' => Yii::t('app', 'Params'),
            'store_count' => I18NUitl::t('app', '{Store}{Count}'),
            'comment_count' => I18NUitl::t('app', '{Comment}{Count}'),
            'click_count' => I18NUitl::t('app', '{Click}{Count}'),
            'share_count' => I18NUitl::t('app', '{Share}{Count}'),
            'like_count' => I18NUitl::t('app', '{Like}{Count}'),
            'sale_count' => I18NUitl::t('app', '{Sale}{Count}'),
            'init_required' => Yii::t('app', 'Init Required'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsCategory()
    {
        return $this->hasOne(GoodsCategory::class, ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsModel()
    {
        return $this->hasOne(GoodsModel::class, ['id' => 'model_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsActions()
    {
        return $this->hasMany(GoodsAction::class, ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsApproves()
    {
        return $this->hasMany(GoodsApprove::class, ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsAttValueRefs()
    {
        return $this->hasMany(GoodsAttValueRef::class, ['goods_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsDetails()
    {
        return $this->hasOne(GoodsDetail::class, ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsTagRefs()
    {
        return $this->hasMany(GoodsTagRef::class, ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::class, ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'owner_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreater()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'updated_by']);
    }

    /**
     * @return array
     */
    public function getGoodsSpecItems()
    {
        $query = (new Query())
            ->select([
                'GoodsSpec.id as spec_id',
                'GoodsSpecItem.goods_id',
                'GoodsSpecItem.id',
                'GoodsSpecItem.value',
            ])
            ->from(['GoodsSpecItem' => GoodsSpecItem::tableName()])
            ->leftJoin(['GoodsSpec' => GoodsSpec::tableName()], 'GoodsSpec.id = GoodsSpecItem.spec_id')
            ->where([
                'GoodsSpecItem.goods_id' => $this->id,
                'GoodsSpec.model_id' => $this->model_id,
                'GoodsSpecItem.is_del' => 0,
                'GoodsSpec.is_del' => 0]);
        return $query->all();
    }

    /**
     * 获取商品价格项
     * @return array Description
     */
    public function getGoodsSpecPrices()
    {
        $query = (new Query())
            ->from(['GoodsSpecPrice' => GoodsSpecPrice::tableName()])
            ->where([
                'GoodsSpecPrice.goods_id' => $this->id,
                'GoodsSpecPrice.is_del' => 0]);
        return $query->all();
    }

    /**
     * 获取可用商品列表
     *
     * @param bool $map 是否返回 key => value 键值对
     * @return array
     */
    public static function getUseableList($map = true)
    {
        $result = self::find()->where(['is_del' => 0])->all();
        return $map ? ArrayHelper::map($result, 'id', 'name') : $result;
    }

}
