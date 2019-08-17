<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shooting_action}}".
 *
 * @property int $id
 * @property int $page_id 页面ID，关联goods_scene_page,id
 * @property string $name 名称
 * @property string $url 路径
 * @property string $des 描述
 * @property int $is_del 是否删除 0否 1是
 * 
 * @property-read GoodsScenePage $page 对应分页
 */
class ShootingAction extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shooting_action}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'is_del'], 'integer'],
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
            'page_id' => Yii::t('app', 'Page'),
            'name' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'Image'),
            'des' => Yii::t('app', 'Des'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }

    /**
     * @return QueryRecord
     */
    public function getPage()
    {
        return $this->hasOne(GoodsScenePage::class, ['id' => 'page_id']);
    }

}
