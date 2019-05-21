<?php

namespace common\models;

use common\models\system\Region;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_address}}".
 *
 * @property int $id
 * @property int $user_id 用户ID,关联user,id
 * @property string $consignee 收货人
 * @property int $province 省，关联re,id
 * @property int $city 市，关联r,id
 * @property int $district 区，关联r,id
 * @property int $town 镇,关联r,id
 * @property string $address 详细地址
 * @property string $zipcode 邮编
 * @property string $phone
 * @property int $is_default 是否设置为默认地址
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class UserAddress extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_address}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'consignee', 'province', 'city', 'district', 'address', 'phone'], 'required'],
            [['user_id', 'province', 'city', 'district', 'town', 'is_default', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['consignee'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
            ['phone', 'string', 'length' => 11, 'notEqual' => '手机号长度不正确'],
            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'filter', 'filter' => 'trim'],
            [['phone'], 'match', 'pattern' => '/^[1][358][0-9]{9}$/'],
            [['zipcode', 'phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'consignee' => Yii::t('app', 'Consignee'),
            'province' => Yii::t('app', 'Province'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'town' => Yii::t('app', 'Town'),
            'address' => Yii::t('app', 'Address'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'phone' => Yii::t('app', 'Phone'),
            'is_default' => Yii::t('app', 'Is Default'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 获取详细版本，包括省市区名称
     */
    public function getDetail(){
        $address = $this->toArray();
        //返回对应省市区名称
        $regionNames = Region::getRegionList(['id' => [$address['province'], $address['city'], $address['district']]]);

        $address['province_name'] = $regionNames[$address['province']];
        $address['city_name'] = $regionNames[$address['city']];
        $address['district_name'] = $regionNames[$address['district']];
        return $address;
    }

}
