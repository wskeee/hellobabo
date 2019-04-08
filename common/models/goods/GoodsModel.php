<?php
namespace common\models\goods;

use common\models\goods\GoodsAttribute;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%goods_model}}".
 *
 * @property int $id
 * @property string $name 类目名
 * @property string $des 描述
 * @property integer $is_del 是否删除 0否1是
 *
 * @property GoodsAttribute[] $goodsAttributes      属性
 * @property GoodsSpec[] $goodsSpecs                规格
 */
class GoodsModel extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%goods_model}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name'], 'string', 'max' => 50],
            [['des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsAttributes() {
        return $this->hasMany(GoodsAttribute::className(), ['model_id' => 'id'])->where(['is_del' => 0]);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getGoodsSpecs() {
        return $this->hasMany(GoodsSpec::className(), ['model_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * 返回模型
     * @param array $condition      查询条件        默认[is_del => 0]
     * @param bool $key_to_value    返回键值对形式
     * @return array(array|Array) 
     */
    public static function getModels($condition = ['is_del' => 0], $key_to_value = true) {
        $query = self::find();
        $query->andFilterWhere($condition);
        $models = $query->all();

        return $key_to_value ? ArrayHelper::map($models, 'id', 'name') : $models;
    }

}
