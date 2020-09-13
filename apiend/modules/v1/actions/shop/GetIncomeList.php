<?php


namespace apiend\modules\v1\actions\shop;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\shop\ShopSaleRecord;
use common\services\ShopService;
use Yii;
use yii\base\Action;
use yii\helpers\StringHelper;

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
        /** @var ShopSaleRecord $item */
        foreach ($res['list'] as &$item) {
            $user_name = $item['order']['consignee'];
            $item['goods_name'] = $item['goods']['goods_name'];
            $item['user_name'] = $this->substr_cut($user_name);
            $item['buy_time'] = date('Y-m-d', $item['created_at']);
            unset($item['order']);
            unset($item['goods']);
        }

        return new Response(Response::CODE_COMMON_OK, null, $res);
    }

    function substr_cut($user_name){
        $strlen     = mb_strlen($user_name, 'utf-8');
        $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }
}