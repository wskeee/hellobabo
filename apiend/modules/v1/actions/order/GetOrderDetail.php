<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\order\OrderGoods;
use common\models\order\WorkflowDelivery;
use common\models\system\Region;
use common\utils\ExpressUtil;
use Yii;

class GetOrderDetail extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id', null);

        $model = Order::findOne(['id' => $order_id, 'created_by' => Yii::$app->user->id]);

        if ($model) {
            $order = $model->toArray();
            $order['address'] = implode("", Region::getRegionName([$order['province'],$order['city'],$order['district'],])).$order['address'];
            $order['created_time'] = date('Y-m-d H:i:s',$order['created_at']);
            $deliver = WorkflowDelivery::findOne(['order_id' => $model->id]);
            
            if($model->order_status == Order::ORDER_STATUS_WAIT_CONFIRM && $deliver){
                $express = ExpressUtil::query($deliver->invoice_no);
                
                if($express['ret'] == 200){
                    $deliver->shipping_code = $express['data']['comid'];
                    $deliver->shipping_name = $express['data']['com'];
                    if($deliver->invoice_no == '472141765618'){
                        $express['data'] = json_decode('{"com": "中通快递","number": "472141765618","site": "www.zto.com","traces": [{"time": "2017-12-26 19:52:30","desc": "[济南市] [济南龙鼎分部]的南清已收件 电话:13113450088"},{"time": "2017-12-26 20:08:28","desc": "[济南市] 快件离开 [济南龙鼎分部]已发往[保定中转]"},{"time": "2017-12-27 00:16:12","desc": "[济南市] 快件到达 [济南中转部]"},{"time": "2017-12-27 00:18:07","desc": "[济南市] 快件离开 [济南中转部]已发往[保定中转]"},{"time": "2017-12-27 14:01:08","desc": "[保定市] 快件到达 [保定中转]"},{"time": "2017-12-27 15:04:50","desc": "[保定市] 快件离开 [保定中转]已发往[保定清苑]"},{"time": "2017-12-28 07:47:43","desc": "[保定市] 快件到达 [保定清苑]"},{"time": "2017-12-28 09:42:27","desc": "[保定市] 快件离开 [保定清苑]已发往[清苑区北王力乡]"},{"time": "2017-12-28 14:34:58","desc": "[保定市] 快件已到达[清苑区北王力乡],业务员于永志正在第1次派件 电话:13463276600 请保持电话畅通、耐心等待"},{"time": "2017-12-28 20:21:42","desc": "[保定市] 快件已被[中通代签的中通温仁站]代签，如有问题请电联[13463276600]，感谢您使用中通快递，期待再次为您服务！"}],"tel": "95311","comid": "ZTO"}');
                    }
                    $deliver->note = json_encode($express['data']);
                    $deliver->save();
                }
            }
            if($deliver && $deliver->note!=""){
                $deliver->note = json_decode($deliver->note, true);
            }
            $goods_list = OrderGoods::find()->where(['order_id' => $order_id, 'is_del' => 0])->all();

            return new Response(Response::CODE_COMMON_OK, null, [
                'order' => $order,
                'deliver' => $deliver,
                'goods_list' => $goods_list,
            ]);
        } else {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Order')]);
        }
    }

}
