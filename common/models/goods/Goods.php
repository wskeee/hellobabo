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

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property int $id
 * @property int $category_id 商品的所属类目ID，关联goods_category表id字段
 * @property int $model_id 模型ID,goods_model表,id
 * @property int $owner_id 当前所有者id，关联admin_user表id字段
 * @property string $goods_sn 商品编码
 * @property string $goods_name 商品名称
 * @property string $goods_cost 成本
 * @property string $goods_price 价格
 * @property string $goods_des 描述/简介
 * @property string $cover_url 封面路径
 * @property string $video_url 视频地址
 * @property int $status 普通状态 1待发布 2已发布 3已下架
 * @property string $tags 标签，多个使用逗号分隔
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
 * @property GoodsDetail[] $goodsDetails
 * @property GoodsTagRef[] $goodsTagRefs
 * @property Issue[] $issues
 * @property Array[] $goodsSpecItems    所有商品规格
 */
class Goods extends ActiveRecord
{
    /* 未发布 */

    const STATUS_UNPUBLISHED = 1;
    /* 已发布 */
    const STATUS_PUBLISHED = 2;
    /* 已下架 */
    const STATUS_SOLD_OUT = 3;

    /* 状态 */

    public static $statusKeyMap = [
        self::STATUS_UNPUBLISHED => '未发布',
        self::STATUS_PUBLISHED => '已发布',
        self::STATUS_SOLD_OUT => '已下架',
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
            [['category_id', 'model_id', 'owner_id', 'status', 'store_count', 'comment_count', 'click_count', 'share_count', 'like_count', 'sale_count', 'init_required', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['goods_cost', 'goods_price'], 'number'],
            [['goods_sn'], 'string', 'max' => 20],
            [['goods_name'], 'string', 'max' => 100],
            [['goods_des', 'cover_url', 'video_url', 'tags'], 'string', 'max' => 255],
            [['tags'], 'tagVerify',],
        ];
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
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category'),
            'model_id' => Yii::t('app', 'Model'),
            'owner_id' => Yii::t('app', 'Owner'),
            'goods_sn' => I18NUitl::t('app', '{Goods}{SN}'),
            'goods_name' => Yii::t('app', 'Name'),
            'goods_cost' => Yii::t('app', 'Cost'),
            'goods_price' => Yii::t('app', 'Price'),
            'goods_des' => Yii::t('app', 'Des'),
            'cover_url' => Yii::t('app', 'Cover'),
            'video_url' => Yii::t('app', 'Video'),
            'status' => Yii::t('app', 'Status'),
            'tags' => Yii::t('app', 'Tag'),
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
        return $this->hasOne(GoodsCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsModel()
    {
        return $this->hasOne(GoodsModel::className(), ['id' => 'model_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsActions()
    {
        return $this->hasMany(GoodsAction::className(), ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsApproves()
    {
        return $this->hasMany(GoodsApprove::className(), ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsAttValueRefs()
    {
        return $this->hasMany(GoodsAttValueRef::className(), ['goods_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsDetails()
    {
        return $this->hasOne(GoodsDetail::className(), ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsTagRefs()
    {
        return $this->hasMany(GoodsTagRef::className(), ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['goods_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'owner_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreater()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'updated_by']);
    }

    /**
     * @return Array
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
     * @return Array Description
     */
    public function getGoodsSpecPrices(){
        $query = (new Query())
                ->from(['GoodsSpecPrice' => GoodsSpecPrice::tableName()])
                ->where([
                    'GoodsSpecPrice.goods_id' => $this->id,
                    'GoodsSpecPrice.is_del' => 0]);
        return $query->all();
    }

}
