<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property string $id
 * @property string $username 用户登录名
 * @property string $nickname 昵称
 * @property int $type 类型 1普通 2作者
 * @property string $auth_key 验证
 * @property string $password_hash 密码
 * @property string $password_reset_token 密码重置令牌
 * @property int $sex 性别：0保密 1男 2女
 * @property string $email 邮箱地址
 * @property string $avatar 头像
 * @property string $guid 企业微信
 * @property string $phone 电话
 * @property int $status 状态：0停用 10启用
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class AdminUser extends BaseUser
{
    /* 普通用户 */
    const TYPE_GENERAL = 1;
    /* 所有者/作者 */
    const TYPE_OWNER = 2;
    /* 代理商 */
    const TYPE_AGENCY = 3;
    /* 用户类型 */
    public static $typeName = [
        self::TYPE_GENERAL => '普通用户',
        self::TYPE_OWNER => '所有者/作者',
        self::TYPE_AGENCY => '代理商',
    ];
    
    public function scenarios() 
    {
        return [
            self::SCENARIO_CREATE => 
                ['username','nickname','type','sex','email','password_hash','password2','email','guid','phone','avatar'],
            self::SCENARIO_UPDATE => 
                ['username','nickname','type','sex','email','password_hash','password2','email','guid','phone','avatar'],
            self::SCENARIO_DEFAULT => ['username','nickname','type']
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return parent::attributeLabels() + [
            'guid' => Yii::t('app', 'Guid'),
            'type' => Yii::t('app', 'Type'),
            ];
    }
    
    /**
     * 按类型获取用户
     * @param int $type
     * @param bool $key_to_map
     * 
     * @return array
     */
    public static function getUserByType($type = self::TYPE_GENERAL , $key_to_map = true){
        $users = self::findAll(['type' => $type , 'status' => self::STATUS_ACTIVE]);
        $keyMaps = [];
        foreach($users as $user){
            $keyMaps[$user->id] = "{$user->nickname} ({$user->phone})";
        }
        return $key_to_map ? $keyMaps : $users;
    }
}
