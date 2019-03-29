<?php

namespace common\models\goods;

use Yii;

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
 */
class GoodsMaterial extends \yii\db\ActiveRecord
{
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
            'goods_id' => Yii::t('app', '所属商品，关联goods表id字段'),
            'name' => Yii::t('app', '素材名'),
            'input_type' => Yii::t('app', '输入方式 1单选 2多选 3单行输入 4多行输入'),
            'sort_order' => Yii::t('app', '排序'),
            'is_required' => Yii::t('app', '是否必选 0否 1是'),
            'is_del' => Yii::t('app', '是否删除'),
            'des' => Yii::t('app', '备注'),
        ];
    }
}
