<?php

namespace common\models\goods;

use common\models\api\ApiResponse;
use common\models\Tags;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%goods_tag_ref}}".
 *
 * @property int $id
 * @property int $goods_id 商品ID,关联goods,id
 * @property int $tag_id 标签ID，关联tags,id
 * @property int $is_del 是否删除：0否 1是
 *
 * @property Goods $goods
 * @property Tags $tags
 */
class GoodsTagRef extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_tag_ref}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id', 'tag_id', 'is_del'], 'integer'],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', '商品ID,关联goods,id'),
            'tag_id' => Yii::t('app', '标签ID，关联tags,id'),
            'is_del' => Yii::t('app', '是否删除：0否 1是'),
        ];
    }
    
    /**
     * 保存商品goods_id标签关联关系
     * @param int $goods_id
     * @param Tags $tags    商品goods_id标签
     * @return ApiResponse
     */
    public static function saveGoodsTagRef($goods_id, $tags)
    {
        try {
            // 如果标签为空则返回
            if(empty($tags)) return;

            //删除已存在的标签
            self::updateAll(['is_del' => 1], ['goods_id' => $goods_id]);
            //准备数据
            $mediaTags = [];
            foreach ($tags as $tag) {
                /* @var $tag Tags */
                $mediaTags[] = [$goods_id, $tag->id];
            }
            
            //保存关联
            \Yii::$app->db->createCommand()->batchInsert(self::tableName(), ['goods_id', 'tag_id'], $mediaTags)->execute();
            //累加引用次数
            Tags::updateAllCounters(['ref_count' => 1], ['id' => ArrayHelper::getColumn($tags, 'id')]);
        }catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
