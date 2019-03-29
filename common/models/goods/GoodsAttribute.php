<?php

namespace common\models\goods;

use common\utils\I18NUitl;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%goods_attribute}}".
 *
 * @property int $id
 * @property int $model_id 所属模型，关联goods_dodel表id字段
 * @property string $name 属性名
 * @property int $index_type 检索方式 0不检查 1关键字检索 2范围检索
 * @property int $input_type 输入方式 1单选 2多选 3单行输入 4多行输入
 * @property int $value_length 值长度
 * @property int $sort_order 排序
 * @property int $is_required 是否必选 0否 1是
 * @property int $is_del 是否删除
 *
 * @property GoodsAttValueRef[] $goodsAttValueRefs
 * @property GoodsModel $goodsModel 模型
 * @property GoodsAttributeValue[] $goodsAttributeValues
 */
class GoodsAttribute extends ActiveRecord
{
    /** 单选-输入类型 */
    const SINGLE_SELECT_INPUT_TYPE = 1;
    
    /** 多选-输入类型 */
    const MULTPLE_SELECT_INPUT_TYPE = 2;
    
    /** 单行-输入类型 */
    const SINGLE_LINE_INPUT_TYPE = 3;
    
    /** 多行-输入类型 */
    const MULTPLE_LINE_INPUT_TYPE = 4;
    
    /**
     * 输入类型
     * @var array 
     */
    public static $inputTypeMap = [
        self::SINGLE_SELECT_INPUT_TYPE => '单选框',
        self::MULTPLE_SELECT_INPUT_TYPE => '多选框',
        self::SINGLE_LINE_INPUT_TYPE => '单行输入',
        self::MULTPLE_LINE_INPUT_TYPE => '多行输入'
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attribute}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_id', 'index_type', 'input_type', 'value_length', 'sort_order', 'is_required', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsModel::className(), 'targetAttribute' => ['model_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_id' => Yii::t('app', 'Model'),
            'name' => Yii::t('app', 'Name'),
            'index_type' => I18NUitl::t('app', 'Is Index'),
            'input_type' => I18NUitl::t('app', '{Input}{Type}'),
            'value_length' => I18NUitl::t('app', '{Value}{Length}'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_required' => Yii::t('app', 'Is Required'),
            'is_del' => Yii::t('app', 'Is Use'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsAttValueRefs()
    {
        return $this->hasMany(GoodsAttValueRef::className(), ['attribute_id' => 'id']);
    }

    /**
     * 模型
     * @return ActiveQuery
     */
    public function getGoodsModel(){
        return $this->hasOne(GoodsModel::class, ['id' => 'model_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsAttributeValues()
    {
        return $this->hasMany(GoodsAttributeValue::className(), ['attribute_id' => 'id']);
    }
}
