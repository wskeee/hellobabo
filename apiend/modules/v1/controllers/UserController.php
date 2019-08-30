<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\user\AddAddress;
use apiend\modules\v1\actions\user\BindInfo;
use apiend\modules\v1\actions\user\DecryptedData;
use apiend\modules\v1\actions\user\DelAddress;
use apiend\modules\v1\actions\user\GetAddressList;
use apiend\modules\v1\actions\user\GetIncomeList;
use apiend\modules\v1\actions\user\GetInfo;
use apiend\modules\v1\actions\user\SetDefaultAddress;
use apiend\modules\v1\actions\user\UpdateAddress;
use apiend\modules\v1\actions\user\UpdateAddressReady;

/**
 * 登录认证
 *
 * @author Administrator
 */
class UserController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            
        ];
        $behaviors['verbs']['actions'] = [
            'get-info' => ['get'],
            'bind-info' => ['post'],
            'decrypted-data' => ['post'],
            'get-address-list' => ['get'],
            'add-address' => ['post'],
            'del-address' => ['post'],
            'update-address' => ['post'],
            'update-address-ready' => ['get'],
            'set-default-address' => ['post'],
            'get-income-list' => ['get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-info' => ['class' => GetInfo::class],
            'bind-info' => ['class' => BindInfo::class],
            'decrypted-data' => ['class' => DecryptedData::class],
            'get-address-list' => ['class' => GetAddressList::class],
            'add-address' => ['class' => AddAddress::class],
            'del-address' => ['class' => DelAddress::class],
            'update-address' => ['class' => UpdateAddress::class],
            'update-address-ready' => ['class' => UpdateAddressReady::class],
            'set-default-address' => ['class' => SetDefaultAddress::class],
            'get-income-list' => ['class' => GetIncomeList::class],
        ];
    }

}
