<?php

namespace common\models\goods;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%goods_detail}}".
 *
 * @property int $id
 * @property int $goods_id 商品id，关联goods表id字段
 * @property string $content 商品详情 描述/简介
 * @property string $mobile_content 移动端商品详情
 *
 * @property Goods $goods
 */
class GoodsDetail extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id'], 'integer'],
            [['content'], 'string'],
            [['mobile_content'], 'arrTostr'],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
        ];
    }
    
    /**
     * 数组转字符
     * 
     * @param string|array $att
     * @return boolean
     */
    public function arrTostr($att){
        $value = $this[$att];
        if(is_array($value)){
            $value = implode(',', array_filter($value));
        }
        $this[$att] = $value;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', 'Goods'),
            'content' => Yii::t('app', 'Details'),
            'mobile_content' => Yii::t('app', 'Details'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        //保存前转码
        $this->content = Html::encode($this->content);
        return true;
    }

    public function afterFind()
    {
        //拿到数据前解码
        $this->content = Html::decode($this->content);
        parent::afterFind();
    }

    /**
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

}
