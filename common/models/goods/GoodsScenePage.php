<?php

namespace common\models\goods;

use common\components\aliyuncs\Aliyun;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%goods_scene_page}}".
 *
 * @property int $id
 * @property int $scene_id 场景ID，关联goods_scene,id
 * @property int $angle_id 角度ID，关联shooting_angle,id
 * @property int $face_id 表情ID，shooting_face,id
 * @property string $source_id 源始ID，adobeId
 * @property string $name 名称
 * @property string $effect_url 效果图路径
 * @property string $source_url 源始路径
 * @property string $pos 位置：left,right,center
 * @property int $is_required 是否必需上传相片 0否 1是
 * @property int $is_del 是否删除
 * @property string $des 备注
 * 
 * @property-read GoodsScene $scene 场景
 * @property-read ShootingAngle $angle 角度
 * @property-read ShootingFace $face 表情
 * @property-read Array<ShootingAction> $actions 动作
 * @property-read GoodsPagePose $pose 人体数据
 */
class GoodsScenePage extends ActiveRecord
{

    const POS_LEFT = 'left';
    const POS_RIGHT = 'right';

    public static $posNameMap = [
        self::POS_LEFT => '左页',
        self::POS_RIGHT => '右页',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_scene_page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scene_id', 'angle_id', 'face_id', 'is_required', 'is_del'], 'integer'],
            [['source_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 50],
            [['effect_url', 'source_url', 'des'], 'string', 'max' => 255],
            [['pos'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'scene_id' => Yii::t('app', 'Scene'),
            'angle_id' => Yii::t('app', 'Shooting Angle'),
            'face_id' => Yii::t('app', 'Shooting Face'),
            'source_id' => Yii::t('app', 'Page Source Id'),
            'name' => Yii::t('app', 'Name'),
            'effect_url' => Yii::t('app', 'Effect Url'),
            'source_url' => Yii::t('app', 'Page Source'),
            'pos' => Yii::t('app', 'Pos'),
            'is_required' => Yii::t('app', 'Page Is Required'),
            'is_del' => Yii::t('app', 'Is Del'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $source_url = $this->source_url;
            if ($this->name == "") {
                $this->name = self::$posNameMap[$this->pos];
            }
            if (pathinfo($source_url, PATHINFO_EXTENSION) == 'zip') {
                $unzipPath = Yii::$app->params['webuploader']['unzipPath'];
                $filename = pathinfo($source_url, PATHINFO_FILENAME);
                $this->source_url = Aliyun::absolutePath("{$unzipPath}{$filename}/skin.js");
            }
            return true;
        }
        return false;
    }

    /**
     * @return ActiveQuery
     */
    public function getScene()
    {
        return $this->hasOne(GoodsScene::class, ['id' => 'scene_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAngle()
    {
        return $this->hasOne(ShootingAngle::class, ['id' => 'angle_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getFace()
    {
        return $this->hasOne(ShootingFace::class, ['id' => 'face_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getActions(){
        return $this->hasMany(ShootingAction::class, ['page_id' => 'id'])->where(['is_del' => 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getPose(){
        return $this->hasOne(GoodsPagePose::class, ['page_id' => 'id']);
    }

}
