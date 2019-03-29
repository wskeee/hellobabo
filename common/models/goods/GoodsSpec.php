<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;
use yii\redis\ActiveQuery;

/**
 * This is the model class for table "{{%goods_spec}}".
 *
 * @property int $id
 * @property int $model_id 模型id,关联model,id
 * @property string $name 规格表
 * @property int $sort_order 排序
 * @property int $is_del 是否已删除：0否 1是
 * @property string $des 描述
 * 
 * @property GoodsModel $goodsModel 模型
 */
class GoodsSpec extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_spec}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_id', 'sort_order', 'is_del'], 'integer'],
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
            'model_id' => Yii::t('app', 'Model'),
            'name' => Yii::t('app', 'Name'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_del' => Yii::t('app', 'Is Use'),
            'des' => Yii::t('app', 'Des'),
        ];
    }
    
    /**
     * 模型
     * @return ActiveQuery
     */
    public function getGoodsModel(){
        return $this->hasOne(GoodsModel::class, ['id' => 'model_id']);
    }
}
