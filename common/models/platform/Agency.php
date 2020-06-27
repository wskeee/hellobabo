<?php

namespace common\models\platform;

use common\models\AdminUser;
use common\models\system\Region;
use common\utils\I18NUitl;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%agency}}".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $des 描述
 * @property string $cover_url 封面
 * @property int $level 等级 1全国 2省 3市 4区 5镇
 * @property int $admin_id 管理员ID admin_user.id
 * @property string $contacts_name 联系人名称
 * @property string $contacts_phone 联系人电话
 * @property string $idcard 身份证
 * @property string $idcard_img_url 身份证图片地址
 * @property int $province 省，关联re,id
 * @property int $city 市，关联r,id
 * @property int $district 区，关联r,id
 * @property int $town 镇,关联r,id
 * @property string $address 详细地址
 * @property int $status 状态 0未完善 1待审核 2审核通过 3审核失败
 * @property int $apply_time 申请时间
 * @property int $check_id 审核人ID，关联 admin_user.id
 * @property int $check_time 审核时间
 * @property string $check_feedback 审核反馈
 * @property int $order_num_all 总订单数
 * @property int $order_num_self 自产订单数
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property string levelName 等级名称
 * @property string regions 地区
 * @property int orderNumAll 代理商所有订单数
 * @property int orderNumSelf 代理商自身订单数
 * @property int orderNumAuto 自动归类订单数
 *
 * @property AdminUser admin 管理员
 * @property AdminUser checker 审核人
 * @property AgencyServicer<Array> servicers 服务人员
 * @property AgencyStatistics<array> statistics 统计
 * @property AgencyStatistics monthStatistics 月统计
 */
class Agency extends ActiveRecord
{
    // 代理等级
    const LEVEL_COUNTRY = 1;
    const LEVEL_PROVINCE = 2;
    const LEVEL_CITY = 3;
    const LEVEL_DISTRICT = 4;

    public static $levelNames = [
        //self::LEVEL_COUNTRY => '全国',
        self::LEVEL_PROVINCE => '省级',
        self::LEVEL_CITY => '市级',
        self::LEVEL_DISTRICT => '区级',
    ];

    // 状态 0未完善 1待审核 2审核通过 3审核失败
    const STATUS_UNREADY = 0;
    const STATUS_CHECKING = 1;
    const STATUS_CHECK_SUCCESS = 2;
    const STATUS_CHECK_FAIL = 3;

    public static $statusNames = [
        self::STATUS_UNREADY => '未完善',
        self::STATUS_CHECKING => '待审核',
        self::STATUS_CHECK_SUCCESS => '审核通过',
        self::STATUS_CHECK_FAIL => '审核失败',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agency}}';
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
            [['level', 'admin_id', 'province', 'city', 'district', 'town', 'status', 'apply_time', 'check_id', 'check_time', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['order_num_all', 'order_num_self', ], 'integer'],
            [['name', 'contacts_name', 'contacts_phone', 'idcard'], 'string', 'max' => 50],
            [['des', 'cover_url', 'idcard_img_url', 'address', 'check_feedback'], 'string', 'max' => 255],
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
            'des' => Yii::t('app', 'Des'),
            'cover_url' => Yii::t('app', 'Cover'),
            'level' => Yii::t('app', 'Level'),
            'levelName' => Yii::t('app', 'Level'),
            'admin_id' => Yii::t('app', 'Administrator'),
            'contacts_name' => I18NUitl::t('app', '{Contacts}{Name}'),
            'contacts_phone' => I18NUitl::t('app', '{Contacts}{Phone}'),
            'idcard' => Yii::t('app', 'Idcard'),
            'idcard_img_url' => Yii::t('app', 'Idcard Img'),
            'province' => Yii::t('app', 'Province'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'town' => Yii::t('app', 'Town'),
            'address' => Yii::t('app', 'Address'),
            'regions' => Yii::t('app', 'Region'),
            'status' => Yii::t('app', 'Status'),
            'apply_time' => Yii::t('app', 'Apply Time'),
            'check_id' => Yii::t('app', 'Check ID'),
            'check_time' => Yii::t('app', 'Check Time'),
            'check_feedback' => Yii::t('app', 'Check Feedback'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'order_num_all' => Yii::t('app', '总订单'),
            'order_num_self' => Yii::t('app', '自身订单'),
        ];
    }

    //---------------------------------------------------------
    //
    // get & set attr
    //
    //---------------------------------------------------------
    /**
     * 等级名称
     * @return mixed
     */
    public function getLevelName()
    {
        return self::$levelNames[$this->level];
    }

    /**
     * 返回所属区域
     * @return string
     */
    public function getRegions()
    {
        $ids = [];
        $this->province && $ids[] = $this->province;
        $this->city && $ids[] = $this->city;
        $this->district && $ids[] = $this->district;
        $this->town && $ids[] = $this->town;
        $regions = Region::getRegionName($ids);
        return implode($regions);
    }

    /**
     * 代理商所有订单数
     * @return float|int
     */
    public function getOrderNumAll()
    {
        if (count($this->statistics)) {
            return array_sum(ArrayHelper::getColumn($this->statistics, 'order_num_all'));
        } else {
            return 0;
        }
    }

    /**
     * 代理商自身订单数
     * @return float|int
     */
    public function getOrderNumSelf()
    {
        if (count($this->statistics)) {
            return array_sum(ArrayHelper::getColumn($this->statistics, 'order_num_self'));
        } else {
            return 0;
        }
    }

    /**
     * 自动归类订单数
     * @return float|int
     */
    public function getOrderNumAuto()
    {
        if (count($this->statistics)) {
            return array_sum(ArrayHelper::getColumn($this->statistics, 'order_num_auto'));
        } else {
            return 0;
        }
    }

    //---------------------------------------------------------
    //
    // with
    //
    //---------------------------------------------------------

    /**
     * @return ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'admin_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getChecker()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'check_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getServicerItems()
    {
        return $this->hasMany(AgencyServicer::class, ['agency_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getServicers()
    {
        return $this->hasMany(AgencyServicer::class, ['id' => 'user_id'])->via('servicerItems');
    }

    /**
     * @return ActiveQuery
     */
    public function getStatistics()
    {
        return $this->hasOne(AgencyStatistics::class, ['agency_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMonthStatistics()
    {
        return $this->hasOne(AgencyStatistics::class, ['agency_id' => 'id'])->where(['time' => date('Ym')]);
    }
}
