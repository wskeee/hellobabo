<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_element}}".
 *
 * @property int $id
 * @property string $name 名称
 * @property int $type 类型：0图片 1sprite 2声音
 * @property string $thumb_url 缩略图
 * @property string $img_url
 * @property string $sound_url
 * @property string $config 配置
 * @property string $des 描述
 */
class GoodsElement extends \yii\db\ActiveRecord
{
    const TYPE_IMAGE = 0;
    const TYPE_SPRITE = 1;
    const TYPE_SOUND = 2;
    
    static $typeNameMap = [
        self::TYPE_IMAGE => '图片',
        self::TYPE_SPRITE => '动画',
        self::TYPE_SOUND => '声音',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_element}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type'], 'integer'],
            [['config'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['thumb_url', 'img_url', 'sound_url', 'des'], 'string', 'max' => 255],
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
            'type' => Yii::t('app', 'Type'),
            'thumb_url' => Yii::t('app', 'Thumb Url'),
            'img_url' => Yii::t('app', 'Img Url'),
            'sound_url' => Yii::t('app', 'Sound Url'),
            'config' => Yii::t('app', 'Config'),
            'des' => Yii::t('app', 'Des'),
        ];
    }
}
