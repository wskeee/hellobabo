<?php

namespace apiend\models;

use common\models\api\ApiResponse;

/**
 * 包括所有 API 错误返馈码及错误描述
 *
 * @author Administrator
 */
class Response extends ApiResponse
{

    //--------------------------------------------------------------------------------------------------------------
    //
    // 所有 CODE 值范围 = 10000-99999 其中 10000-10099 为保留范围，请勿使用
    // 
    //--------------------------------------------------------------------------------------------------------------
    //
    //--------------------------------------------------------------------------------------------------------------
    //
    // 账号 CODE 值范围 = 10100-10199
    // 
    //--------------------------------------------------------------------------------------------------------------
    /** 登录验证失败 */
    const CODE_USER_AUTH_FAILED = '10100';
    /** 该用户名已经注册 */
    const CODE_USER_USERNAME_HAS_REGISTERED = '10101';
    /** 该手机号已经注册 */
    const CODE_USER_PHONE_HAS_REGISTERED = '10102';
    /** 注册失败 */
    const CODE_USER_REGISTER_FAILED = '10103';
    /** 第三方账号已存在 */
    const CODE_USER_AUTH_ACCOUNT_EXISTS = '10104';

    /* 提现金额校验失败 */
    const CODE_USER_MONEY_VERIFICATION_FAILED = '10150';
    /* 提现失败 */
    const CODE_USER_MONEY_WITHDRAWAL_FAILED = '10151';


    //--------------------------------------------------------------------------------------------------------------
    //
    // SMS CODE 值范围 = 10200-10299
    // 
    //--------------------------------------------------------------------------------------------------------------
    /** 验证码不匹对 */
    const CODE_SMS_AUTH_FAILED = '10200';
    /** 验证码已失效 */
    const CODE_SMS_INVALID = '10201';
    /** 发送失败 */
    const CODE_SMS_SEND_FAILED = '10202';
    /** 找不到对应模板 */
    const CODE_SMS_TEMPLATE_NOT_FOUND = '10203';

    //--------------------------------------------------------------------------------------------------------------
    //
    // ORDER CODE 值范围 = 10300-10399
    // 
    //--------------------------------------------------------------------------------------------------------------
    /* 没存库 */
    const CODE_ORDER_STORE_COUNT_OUT = '10300';
    /* 下单失败 */
    const CODE_ORDER_CREATE_FAILED = '10301';
    /** 重复支付  */
    const CODE_ORDER_PAY_REPEATED = '10302';
    /* 订单取消失败 */
    const CODE_ORDER_CANCEL_FAILED = '10303';
    /* 保存预订单失败 */
    const CODE_ORDER_SAVE_TRY_FAILED = '10304';

    //--------------------------------------------------------------------------------------------------------------
    //
    // ORDER CODE 值范围 = 10400-10499
    // 
    //--------------------------------------------------------------------------------------------------------------
    /* 超过每日最大投票数 */
    const CODE_VOTE_OVER_DAY_MAX = '10400';

    //--------------------------------------------------------------------------------------------------------------
    //
    // ORDER GROUPON CODE 值范围 = 10500-10599
    // 
    //--------------------------------------------------------------------------------------------------------------
    /* 团购创建失败 */
    const CODE_ORDER_GROUPON_CREATE_FAIL = '10500';

    //--------------------------------------------------------------------------------------------------------------
    //
    // ORDER COUPON CODE 值范围 = 10600-10699
    //
    //--------------------------------------------------------------------------------------------------------------
    /* 领卷失败 */
    const CODE_ORDER_COUPON_RECEIVE_FAIL = '10600';

    //--------------------------------------------------------------------------------------------------------------
    //
    // 对应描述
    // 
    //--------------------------------------------------------------------------------------------------------------
    /**
     * 返回 code 与 反馈修改的对应关系
     * 使用时由子类合并使用，注意：请使用  + 号合并数组，保留原来键值
     */
    public function getCodeMap()
    {
        return parent::getCodeMap() + [
                /* USER */
                self::CODE_USER_AUTH_FAILED => '登录验证失败',
                self::CODE_USER_USERNAME_HAS_REGISTERED => '该用户名已经注册',
                self::CODE_USER_PHONE_HAS_REGISTERED => '该手机号已经注册',
                self::CODE_USER_REGISTER_FAILED => '注册失败',
                self::CODE_USER_AUTH_ACCOUNT_EXISTS => '第三方账号已存在',

                self::CODE_USER_MONEY_VERIFICATION_FAILED => '金额校验失败',
                self::CODE_USER_MONEY_WITHDRAWAL_FAILED => '提现失败：{param}',

                /* SMS */
                self::CODE_SMS_AUTH_FAILED => '验证码不匹对',
                self::CODE_SMS_INVALID => '验证码已失效',
                self::CODE_SMS_SEND_FAILED => '发送失败',
                self::CODE_SMS_TEMPLATE_NOT_FOUND => '找不到对应模板',

                /* ORDER */
                self::CODE_ORDER_STORE_COUNT_OUT => '商品已售罄！',
                self::CODE_ORDER_CREATE_FAILED => '下单失败！',
                self::CODE_ORDER_PAY_REPEATED => '重复支付！',
                self::CODE_ORDER_CANCEL_FAILED => '订单取消失败！',
                self::CODE_ORDER_SAVE_TRY_FAILED => '保存订单失败！',

                /* VOTE */
                self::CODE_VOTE_OVER_DAY_MAX => '每天最多只能投 {param} 票',

                /* 团购 */
                self::CODE_ORDER_GROUPON_CREATE_FAIL => '创建团购失败',

                /* 优惠卷 */
                self::CODE_ORDER_COUPON_RECEIVE_FAIL => '领卷失败'
            ];
    }
}
