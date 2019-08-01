<?php
return [
    'adminEmail' => 'admin@example.com',
    /* api */
    'api_secret_key' => 'hellobabo_api',
    
    /* 微信小程序配置 */
    'wechatPaymentConfig' => [
        'app_id' => 'wx69f4a713347ecd2e',
        'mch_id'             => '1533811161',
        'key'                => 'efdgeqpypclk509cyjfxbj9k15fge7pi',   // API 密钥

        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        'cert_path'          => '@apiend/web/cert/apiclient_cert.pem/',     // XXX: 绝对路径！！！！
        'key_path'           => '@apiend/web/cert/apiclient_key.pem',       // XXX: 绝对路径！！！！
        //'sandbox'            => true,
        'notify_url'         => 'http://shareline.free.idcfengye.com/v1/wx-pay/pay-cb',     // 你也可以在下单时单独设置来想覆盖它
    ],
    // 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [
        'app_id' => 'wx69f4a713347ecd2e',
        'secret' => '55ac60d686d8f67fae0149a2032e86db',
        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
        'log' => [
            'level' => 'debug',
            'file' => __DIR__ . '/wechat.log',
        ],
    ],
    // 微信开放平台第三方平台配置 具体可参考EasyWechat
    'wechatOpenPlatformConfig' => [],
    // 微信企业微信配置 具体可参考EasyWechat
    'wechatWorkConfig' => [],
    // 微信企业微信开放平台 具体可参考EasyWechat
    'wechatOpenWorkConfig' => [],
];
