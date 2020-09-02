<?php

namespace common\models\order;

use common\models\goods\GoodsScenePage;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "hbabo_order_goods_scene_page".
 *
 * @property int $id
 * @property int $order_goods_id 订单商品ID，关联order_goods,id
 * @property int $order_goods_scene_id 订单已选场景ID，关联order_goods_scene,id
 * @property int $page_id 场景页ID，关联goods_scene_page,id
 * @property string $source_id 源始ID，adobeID
 * @property string $finish_id 成品ID,adobeID
 * @property string $name 名称
 * @property string $effect_url 效果图路径
 * @property string $source_url 源始路径
 * @property string $user_img_url 用户上传的图片路径
 * @property string $finish_url 成品地址
 * @property string $user_data 用户数据
 * @property string $pos 位置：left,right,center
 * @property int $sort_order 排序
 * @property int $is_required 是否必需上传相片 0否 1是
 * @property int $is_del 是否删除
 * @property int $is_hide 是否隐藏
 * @property string $des 备注
 *
 * @property GoodsScenePage sourcePage;
 * @property OrderGoodsSceme $scene 场景
 * @property OrderGoods $orderGoods 订单商品
 */
class OrderGoodsScenePage extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods_scene_page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_goods_id', 'order_goods_scene_id', 'page_id', 'sort_order', 'is_required', 'is_del', 'is_hide'], 'integer'],
            [['source_id', 'finish_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 50],
            [['effect_url', 'source_url', 'user_img_url', 'finish_url', 'des'], 'string', 'max' => 255],
            [['pos'], 'string', 'max' => 10],
            [['user_data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_goods_id' => Yii::t('app', 'Order Goods ID'),
            'order_goods_scene_id' => Yii::t('app', 'Order Goods Scene ID'),
            'page_id' => Yii::t('app', 'Page ID'),
            'source_id' => Yii::t('app', 'Source ID'),
            'finish_id' => Yii::t('app', 'Finish ID'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'source_url' => Yii::t('app', 'Source Url'),
            'user_img_url' => Yii::t('app', 'User Img Url'),
            'finish_url' => Yii::t('app', 'Finish Url'),
            'pos' => Yii::t('app', 'Pos'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_required' => Yii::t('app', 'Is Required'),
            'is_del' => Yii::t('app', 'Is Del'),
            'is_hide' => Yii::t('app', 'Is Hide'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

    /**
     * 源页
     *
     * @return ActiveQuery
     */
    public function getSourcePage()
    {
        return $this->hasOne(GoodsScenePage::class, ['id' => 'page_id']);
    }

    /**
     * 场景
     *
     * @return ActiveQuery
     */
    public function getScene()
    {
        return $this->hasOne(OrderGoodsScene::class, ['id' => 'order_goods_scene_id']);
    }


    /**
     * 场景
     *
     * @return ActiveQuery
     */
    public function getOrderGoods()
    {
        return $this->hasOne(OrderGoods::class, ['id' => 'order_goods_id']);
    }

    /**
     * 初始分页数据
     *
     * @param OrderGoods $model 订单商品
     * @return int
     */
    public static function initPage($model)
    {
        $query = (new Query())
            ->select([
                'OrderGoodsScene.id order_goods_scene_id', 'OrderGoodsScene.sort_order scene_sort_order',
                'ScenePage.*',
                'OrderGoodsScene.effect_url' // 使用场景预览图替换
            ])
            ->from(['OrderGoodsScene' => OrderGoodsScene::tableName()])
            ->leftJoin(['ScenePage' => GoodsScenePage::tableName()], 'ScenePage.scene_id = OrderGoodsScene.scene_id')
            ->where([
                'OrderGoodsScene.order_goods_id' => $model->id,
                'OrderGoodsScene.is_del' => 0,
                'ScenePage.is_del' => 0,
            ])->orderBy(['scene_sort_order' => SORT_ASC, 'pos' => SORT_ASC]);

        $pages = $query->all();
        $rows = [];
        $sort_order = 0;
        foreach ($pages as $page) {
            $rows[] = [
                $model->id,
                $page['order_goods_scene_id'],
                $page['id'],
                $page['source_id'],
                $page['name'],
                $page['effect_url'],
                $page['source_url'],
                $page['pos'],
                $page['is_required'],
                $page['is_hide'],
                $page['des'],
                $sort_order++,
            ];
        }
        // 清除旧数据
        //OrderGoodsScenePage::updateAll(['is_del' => 1], ['order_goods_id' => $model->id]);

        $result = Yii::$app->db->createCommand()->batchInsert(OrderGoodsScenePage::tableName(), [
            'order_goods_id', 'order_goods_scene_id', 'page_id', 'source_id', 'name', 'effect_url', 'source_url', 'pos', 'is_required', 'is_hide', 'des', 'sort_order'
        ], $rows)->execute();

        return $result;
    }

}
