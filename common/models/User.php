<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username               用户名
 * @property string $nickname               昵称或者真实名称
 * @property integer $type                  用户类型：1散户 2企业用户
 * @property string $password_hash          密码
 * @property string $password_reset_token   密码重置口令
 * @property int $sex                       姓别：0保密 1男 2女
 * @property string $phone                  电话
 * @property string $email                  邮箱
 * @property string $avatar                 头像
 * @property int $status                    状态：0 停用 10启用
 * @property string $des                    简介
 * @property string $auth_key               认证
 * @property string $from                   用户来源，默认本站，也会其它站点用户
 * @property string $money                  当前金额
 * @property string $money_sign             金额校验
 * @property string $access_token           访问令牌
 * @property string $access_token_expire_time           访问令牌到期时间
 * @property string $created_at             创建时间
 * @property string $updated_at             更新时间
 * @property string $password write-only password
 *
 * @property UserGameData $gameData 用户游戏数据
 *
 */
class User extends BaseUser implements IdentityInterface
{

    /**
     * 账号
     * @var array
     */
    public static $statusIs = [
        self::STATUS_STOP => '停用',
        self::STATUS_ACTIVE => '启用',
    ];

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE =>
                ['username', 'nickname', 'sex', 'email', 'password_hash', 'password2', 'phone', 'avatar', 'des'],
            self::SCENARIO_UPDATE =>
                ['username', 'nickname', 'sex', 'email', 'password_hash', 'password2', 'phone', 'avatar', 'des'],
            self::SCENARIO_DEFAULT =>
                ['username', 'nickname', 'sex', 'email', 'password_hash', 'password2', 'phone', 'avatar', 'des'],
            self::SCENARIO_OAUTH => ['username'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password_hash', 'password2'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['username', 'nickname', 'phone'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['username'], 'string', 'max' => 36, 'on' => [self::SCENARIO_CREATE]],
            [['username'], 'checkUsername', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['id', 'username'], 'unique'],
            [['password_hash'], 'string', 'min' => 6, 'max' => 64],
            [['created_at', 'updated_at', 'sex',], 'integer'],
            [['money'], 'number'],
            [['des'], 'string'],
            [['auth_key', 'money_sign'], 'string', 'max' => 32],
            [['username', 'nickname'], 'string', 'max' => 50],
            [['phone'], 'string', 'min' => 11, 'max' => 50],
            [['password_reset_token', 'email', 'avatar'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 2],
            [['email'], 'email'],
            [['avatar'], 'image'],
            [['password2'], 'compare', 'compareAttribute' => 'password_hash'],
            [['avatar'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png'],
        ];
    }

    /**
     * 检查用户名是否为数字或字母及其组合
     * @param string $attribute username
     * @param string $params
     * @return boolean
     */
    public function checkUsername($attribute, $params)
    {
        $regex = '/[\x7f-\xff]/';
        if (preg_match($regex, $this->getAttribute($attribute))) {
            $this->addError($attribute, "用户名不能包含中文！");
            return false;
        } else {
            return true;
        }
    }

    /**
     * 余额校检
     */
    public function moneyVerification()
    {
        if ($this->money == 0 && $this->money_sign == '') {
            return true;
        }
        $money = floatval($this->money);
        return $this->money_sign == md5("wskeee{$this->id}{$money}wskeee");
    }

    /**
     * 创建余额验证
     * @param int $id
     * @param int $money
     * @return string
     */
    public static function makeVerification($id, $money)
    {
        $money = floatval($money);
        return md5("wskeee{$id}{$money}wskeee");
    }

    /**
     * @return ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasOne(UserAuths::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGameData()
    {
        return $this->hasOne(UserGameData::class, ['user_id' => 'id']);
    }

    public static function getAllUser()
    {
        $result = self::find()->select(['id', 'nickname'])
            ->where(['status' => self::STATUS_ACTIVE])
            ->where(['<>', 'nickname', ''])
            ->all();
        return ArrayHelper::map($result, 'id', 'nickname');
    }
}
