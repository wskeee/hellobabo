<?php

namespace common\models\goods;

use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%goods_scene}}".
 *
 * @property int $id
 * @property int $goods_id 所属商品，关联goods表id字段
 * @property int $group_id 分组id,关联scene_group,id
 * @property string $name 场景名
 * @property string $effect_url 效果图路径
 * @property string $demo_url 参考路径
 * @property string $source_url 源图路径
 * @property int $sort_order 排序
 * @property string $pos 位置 start,center,end
 * @property int $is_demo 是否为样例 0否 1是
 * @property int $immutable 不可选 0否 1是
 * @property int $is_required 是否必选 0否 1是
 * @property int $is_selected 默认选中 0否 1是
 * @property int $is_del 是否删除
 * @property string $des 备注
 *
 * @property Goods goods 商品
 * @property SceneGroup group 分组
 */
class GoodsScene extends ActiveRecord
{
    const POS_START = 'start';
    const POS_CENTER = 'center';
    const POS_END = 'end';

    public static $posNameMap = [
        self::POS_START => '开头',
        self::POS_CENTER => '中间',
        self::POS_END => '结尾',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_scene}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'name'], 'required'],
            [['goods_id', 'group_id', 'sort_order', 'is_demo', 'immutable', 'is_required', 'is_selected', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['pos'], 'string', 'max' => 10],
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
            'goods_id' => Yii::t('app', 'Goods'),
            'group_id' => Yii::t('app', 'Group'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'demo_url' => Yii::t('app', 'Demo Url'),
            'source_url' => Yii::t('app', 'Source Url'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'pos' => Yii::t('app', 'Pos'),
            'is_demo' => I18NUitl::t('app', '{Demo}'),
            'immutable' => Yii::t('app', 'Immutable'),
            'is_required' => Yii::t('app', 'Required Img'),
            'is_selected' => Yii::t('app', 'Default Selected'),
            'is_del' => Yii::t('app', 'Is Del'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

    /**
     *
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::class, ['id' => 'goods_id']);
    }

    /**
     *
     * @return ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(SceneGroup::class, ['id' => 'group_id']);
    }

    /**
     * 获取商品场景
     *
     * @param int $goods_id 商品ID
     * @param bool $map 是否以键值对返回
     */
    public static function getGoodsScene($goods_id, $map = true)
    {
        $result = self::find()->where(['goods_id' => $goods_id, 'is_del' => 0])->all();
        return $map ? ArrayHelper::map($result, 'id', 'name') : $result;
    }

    /**
     * 获取商品对应场景预览
     *
     * @param int $goods_id
     * @param int $material_value_id
     */
    public static function getGoodsPreview($goods_id, $material_value_id)
    {
        $result = self::find()
            ->select(['scene.*','scene.id scene_id'])
            ->alias('scene')
            ->innerJoin(['material_rel' => GoodsSceneMaterial::tableName()], 'scene.id = material_rel.scene_id AND material_rel.is_del = 0')
            ->where([
                'scene.goods_id' => $goods_id,
                'material_rel.material_value_id' => $material_value_id,
                'scene.immutable' => 0,
                'scene.is_del' => 0
            ])
            ->orderBy('scene.sort_order asc')
            ->asArray()->all();
        return $result;
    }

}
