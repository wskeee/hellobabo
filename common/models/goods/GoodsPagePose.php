<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_page_pose}}".
 *
 * @property int $page_id page ID 关联 goods_scene_page id
 * @property string $pose_data pose 创形数据
 * @property string $required_data 目标(评分标准)数据
 * @property string $filepath 数据来源
 *
 * @property GoodsScenePage page   场景页
 */
class GoodsPagePose extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_page_pose}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'pose_data'], 'required'],
            [['page_id'], 'integer'],
            [['pose_data','required_data','filepath'], 'string'],
            [['page_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('app', 'Page ID'),
            'pose_data' => Yii::t('app', 'Pose Data'),
            'required_data' => Yii::t('app', 'Required Data'),
            'filepath' => Yii::t('app', 'File'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage(){
        return $this->hasOne(GoodsScenePage::class,['id' => 'page_id']);
    }
}
