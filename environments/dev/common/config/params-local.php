<?php

return [
    /* 阿里云OSS配置 */
    'aliyun' => [
        'accessKeyId' => 'LTAIcHRbE9LWXKL7',
        'accessKeySecret' => 'OVwOCkxVghIrQPrCyvAA4UREPoXVqD',
        'oss' => [
            'bucket-input' => 'wskeee-studying8',
            'bucket-output' => 'wskeee-studying8',
            'host-input' => 'wskeee-studying8.oss-cn-shenzhen.aliyuncs.com',
            'host-output' => 'file.wskeee.top',
            'host-input-internal' => 'wskeee-studying8.oss-cn-shenzhen.aliyuncs.com', //测试使用外网地址
            'host-output-internal' => 'wskeee-studying8.oss-cn-shenzhen.aliyuncs.com', //测试使用外网地址
            'endPoint' => 'oss-cn-shenzhen.aliyuncs.com',
            'endPoint-internal' => 'oss-cn-shenzhen.aliyuncs.com', //测试使用外网地址
        ],
        'mts' => [
            'region_id' => 'cn-shenzhen', //区域
            'pipeline_id' => 'b1fe3fe97b6b42e499cac7969161f5d5', //管道ID
            'pipeline_name' => 'mts-service-pipeline', //管道名称
            'oss_location' => 'oss-cn-shenzhen', //作业输入，华南1
            'template_id_ld' => '015f0c886c3b468f8908fb05784b760d', //流畅模板ID
            'template_id_sd' => '85136b20ecea44d1ae980c43d93a9d6e', //标清模板ID
            'template_id_hd' => '719d5f1c54fa4f5d8042eaf0e20e46ec', //高清模板ID
            'template_id_fd' => 'd97820623858459fbf7a14913e00039b', //超畅模板ID
            'water_mark_template_id' => '9b8da9c8bd234fabae1d88d1581a8435', //水印模板ID 默认右上
            'topic_name' => 'studying8-transcode', //消息通道名
            'transcode_save_path' => 'hellobabo/transcode/', //转码后保存路径
            'screenshot_save_path' => 'hellobabo/thumb/', //截图后保存路径
        ]
    ],
    /* 绘本配置 */
    'hellobabo' => [
        'preview_url' => 'http://tt.hellobabo.wskeee.top/show/default/preview',
        'source_preview_url' => 'http://tt.hellobabo.wskeee.top/show/default/source-preview',
        'ug_url' => 'http://tt.hellobabo.wskeee.top/show/default/ug',
    ],
];
