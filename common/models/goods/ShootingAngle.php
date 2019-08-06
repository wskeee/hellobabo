<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%shooting_angle}}".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $url 路径
 * @property string $des 描述
 * @property int $is_del 是否删除 0否 1是
 */
class ShootingAngle extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shooting_angle}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_del'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['url', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'Image'),
            'des' => Yii::t('app', 'Des'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }
    
    public static function getValues(){
        return ArrayHelper::map(self::find()->where(['is_del' => 0])->all(), 'id', 'name');
    }
}
