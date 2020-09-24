<?php


namespace apiend\modules\v1\actions\shop;


use apiend\models\Response;
use common\models\shop\ShopSaleRecord;
use common\services\ShopService;
use common\utils\StringUtil;
use Yii;
use yii\base\Action;

/**
 * 获取收益列表
 * Class GetIncomeList
 * @package apiend\modules\v1\actions\shop
 */
class GetIncomeList extends Action
{
    public function run()
    {
        $shop_id = Yii::$app->request->getQueryParam('shop_id');
        $year = Yii::$app->request->getQueryParam('year', '');
        $month = Yii::$app->request->getQueryParam('month', '');
        $page = Yii::$app->request->getQueryParam('page', 1);
        $page_size = Yii::$app->request->getQueryParam('page_size', 20);

        $start_time = $end_time = null;
        if ($year && $month) {
            $start_time = strtotime("first day of $year-$month");
            $end_time = strtotime("last day of $year-$month 23:59:59");
        } else if ($year) {
            $start_time = strtotime("first day of $year-01");
            $end_time = strtotime("last day of $year-12 23:59:59");
        }

        $res = ShopService::getIncomeList(['shop_id' => $shop_id], $start_time, $end_time, $page, $page_size);
        $res['data']['all_income'] = round($res['data']['all_income'] * 100)/100;
        /** @var ShopSaleRecord $item */
        foreach ($res['list'] as &$item) {
            $user_name = $item['order']['consignee'];
            $item['goods_name'] = $item['goods']['goods_name'];
            $item['user_name'] = StringUtil::str_hide($user_name);
            $item['buy_time'] = date('Y-m-d', $item['created_at']);
            unset($item['order']);
            unset($item['goods']);
        }

        return new Response(Response::CODE_COMMON_OK, null, $res);
    }

}