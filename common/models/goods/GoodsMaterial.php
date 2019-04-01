<?php

namespace common\models\goods;

use common\models\goods\Goods;
use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%goods_material}}".
 *
 * @property int $id
 * @property int $goods_id 所属商品，关联goods表id字段
 * @property string $name 素材名
 * @property int $input_type 输入方式 1单选 2多选 3单行输入 4多行输入
 * @property int $sort_order 排序
 * @property int $is_required 是否必选 0否 1是
 * @property int $is_del 是否删除
 * @property string $des 备注
 * 
 * @property Goods $goods 商品
 */
class GoodsMaterial extends ActiveRecord
{

    /** 单选-输入类型 */
    const TYPE_SINGLE_SELECT = 1;
    
    /** 多选-输入类型 */
    const TYPE_MULTPLE_SELECT = 2;
    
    /** 单行-输入类型 */
    const TYPE_SINGLE_INPUT = 3;
    
    /** 多行-输入类型 */
    const TYPE_MULTPLE_INPUT = 4;
    
    /**
     * 输入类型
     * @var array 
     */
    public static $inputTypeMap = [
        self::TYPE_SINGLE_SELECT => '单选框',
        self::TYPE_MULTPLE_SELECT => '多选框',
        self::TYPE_SINGLE_INPUT => '单行输入',
        self::TYPE_MULTPLE_INPUT => '多行输入'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_material}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'input_type', 'sort_order', 'is_required', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['des'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', 'Name'),
            'input_type' => I18NUitl::t('app', '{Input}{Type}'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_required' => Yii::t('app', 'Is Required'),
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

}
