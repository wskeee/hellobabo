<?php

namespace common\models\platform;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%express}}".
 *
 * @property int $id
 * @property string $code 代码
 * @property string $name 名称
 * @property string $logo_url logo地址
 * @property string $des 描述
 * @property string $config 配置
 * @property int $is_del 是否删除 0否 1是
 */
class Express extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%express}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['config'], 'string'],
            [['is_del'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 50],
            [['logo_url', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'logo_url' => Yii::t('app', 'Logo'),
            'des' => Yii::t('app', 'Des'),
            'config' => Yii::t('app', 'Config'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }
    
    public static function getExpress(){
        return ArrayHelper::map(self::find()->where(['is_del'=>0])->all(), 'code', 'name');
    }
}
