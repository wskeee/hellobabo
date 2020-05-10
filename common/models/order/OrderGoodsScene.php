<?php

namespace common\models\order;

use common\models\goods\GoodsScene;
use common\models\goods\GoodsSceneMaterial;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "{{%order_goods_scene}}".
 *
 * @property int $id
 * @property int $order_id 订单ID，关联Order,id
 * @property int $scene_id 场景ID
 * @property string $name 场景名
 * @property string $effect_url 效果图路径
 * @property string $demo_url 参考路径
 * @property string $source_url 源图路径
 * @property int $sort_order 排序
 * @property int $immutable 不可更改 0否 1是
 * @property int $is_required 是否必选 0否 1是
 * @property int $is_del 是否删除
 * @property string $des 备注
 *
 * @property GoodsScene $scene 场景
 */
class OrderGoodsScene extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods_scene}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'scene_id', 'sort_order', 'immutable', 'is_required', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['effect_url', 'demo_url', 'source_url', 'des'], 'string', 'max' => 255],
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
            'scene_id' => Yii::t('app', 'Scene'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'demo_url' => Yii::t('app', 'Demo Url'),
            'source_url' => Yii::t('app', 'Source Url'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'immutable' => Yii::t('app', 'Immutable'),
            'is_required' => Yii::t('app', 'Is Required'),
            'is_del' => Yii::t('app', 'Is Del'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getScene()
    {
        return $this->hasOne(GoodsScene::class, ['id' => 'scene_id']);
    }

    /**
     * 初始默认场景
     *
     * @param OrderGoods $order_goods 订单商品
     * @param array $scene_ids 场景ID
     * @param int $material_value_id 素材ID
     */
    public static function initDefaultScene($order_goods, $scene_ids, $material_value_id)
    {
        $scenes = GoodsScene::find()->alias('scene')
            ->select(['scene.*', 'IFNULL(material_rel.effect_url,scene.effect_url) AS effect_url'])
            ->leftJoin(['material_rel' => GoodsSceneMaterial::tableName()],
                "material_rel.scene_id = scene.id AND material_rel.material_value_id = $material_value_id AND material_rel.is_del = 0")
            ->where([
                'scene.goods_id' => $order_goods->goods_id,
                'scene.is_del' => 0])
            ->andWhere(['or', ['scene.id' => $scene_ids], ['scene.is_selected' => 1 , 'material_rel.scene_id = scene.id']])
            ->orderBy('scene.sort_order asc')
            ->asArray()
            ->all();

        if (empty($scenes)) {
            return 0;
        }

        $rows = [];
        $sort_order = 0;
        /**
         * 批量插入场景与订单关联
         * @var GoodsScene $scene
         */
        foreach ($scenes as $scene) {
            $rows [] = [
                $order_goods->id,
                $scene['id'],
                $scene['name'],
                $scene['effect_url'],
                $scene['demo_url'],
                $scene['source_url'],
                $scene['is_required'],
                $scene['des'],
                $scene['immutable'],
                $sort_order++,
            ];
        }
        $result = Yii::$app->db->createCommand()->batchInsert(OrderGoodsScene::tableName(), [
            'order_goods_id',
            'scene_id',
            'name',
            'effect_url',
            'demo_url',
            'source_url',
            'is_required',
            'des',
            'immutable',
            'sort_order'
        ], $rows)->execute();
        return $result;
    }

}
