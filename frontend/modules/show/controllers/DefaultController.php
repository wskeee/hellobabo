<?php

namespace frontend\modules\show\controllers;

use common\models\goods\Goods;
use common\models\goods\GoodsScene;
use common\models\goods\GoodsScenePage;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsScenePage;
use yii\db\Query;
use yii\web\Controller;

/**
 * Default controller for the `show` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 展示平台商品
     */
    public function actionPg($gid = 2, $readyonly = 0)
    {
        $query = (new Query())
            ->select([
                'Scene.id scene_id',
                'Scene.pos scene_pos',
                'Scene.sort_order scene_sort_order',
                'Page.id page_id',
                'Page.pos page_pos',
                'Page.source_id',
                'Page.source_url'
            ])
            ->from(['Scene' => GoodsScene::tableName()])
            ->leftJoin(['Page' => GoodsScenePage::tableName()], 'Scene.id = Page.scene_id')
            ->where([
                'Scene.goods_id' => $gid,
                'Scene.is_selected' => 1,
                'Scene.is_demo' => 1,
                'Scene.is_del' => 0,
                'Page.is_del' => 0,
            ])
            ->orderBy([
                'scene_sort_order' => SORT_ASC,
                'page_pos' => SORT_ASC,
            ]);

        $pages = $query->all();

        /**
         * appData
         */
        $goods = Goods::findOne(['id' => $gid]);
        $goods_params = json_decode($goods->params, true);
        $goods_width = isset($goods_params['width']) ? $goods_params['width'] : 1080;
        $goods_height = isset($goods_params['height']) ? $goods_params['height'] : 1778;
        $app_data = [
            'goods' => ['gid' => $gid, 'type' => 1, 'commission' => $goods->commission, 'orientation' => $goods->orientation, 'width' => $goods_width, 'height' => $goods_height],
            'common' => ['id' => "E4019F635D6CBC488C42C0A8B05F0249", 'path' => 'common/'],
        ];
        $scenes = [];
        foreach ($pages as $page) {
            $scenes [] = [
                'id' => $page['source_id'],
                'path' => $this->replaceHttps(pathinfo($page['source_url'], PATHINFO_DIRNAME) . '/'),
                'lock' => false
            ];
        }
        $app_data['scenes'] = $scenes;
        if ($readyonly) {
            $app_data['showShare'] = $app_data['showBuy'] = false;
        }

        return $this->render('perview', ['app_data' => $app_data]);
    }

    /**
     * 展示用户商品
     */
    public function actionUg($ogid, $readyonly = 0)
    {
        $pages = OrderGoodsScenePage::find()
            ->where(['order_goods_id' => $ogid, 'is_del' => 0, 'is_hide' => 0])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        $orderGoods = OrderGoods::findOne(['id' => $ogid]);
        $goods_params = json_decode($orderGoods->goods->params, true);
        $goods_width = isset($goods_params['width']) ? $goods_params['width'] : 1080;
        $goods_height = isset($goods_params['height']) ? $goods_params['height'] : 1778;
        $app_data = [
            'goods' => ['gid' => $orderGoods->goods->id, 'ogid' => $ogid, 'type' => 2, 'commission' => $orderGoods->goods->commission, 'orientation' => $orderGoods->goods->orientation, 'width' => $goods_width, 'height' => $goods_height],
            'common' => ['id' => "E4019F635D6CBC488C42C0A8B05F0249", 'path' => 'common/'],
        ];
        $scenes = [];
        foreach ($pages as $page) {
            $scenes [] = $this->createScene($page, true);
        }
        $app_data['scenes'] = $scenes;
        if ($readyonly) {
            $app_data['showShare'] = $app_data['showBuy'] = false;
        }
        return $this->render('perview', ['app_data' => $app_data]);
    }

    /**
     * 预览绘本 source 页
     * @param string $page_ids id,id,id 显示预览的页面id
     * @param string $target 显示预览的目标属性 有 source 和 finish
     *
     * @return type
     */
    public function actionSourcePreview($page_ids, $target = 'source')
    {
        $pages = GoodsScenePage::find()
            ->where(['id' => explode(',', $page_ids)])
            ->all();
        /** @var GoodsScenePage $page */
        $page = $pages[0];
        $goods_params = json_decode($page->scene->goods->params, true);
        $goods_width = isset($goods_params['width']) ? $goods_params['width'] : 1080;
        $goods_height = isset($goods_params['height']) ? $goods_params['height'] : 1778;
        $app_data = [
            'goods' => ['gid' => $page->scene->goods_id, 'orientation' => $page->scene->goods->orientation, 'width' => $goods_width, 'height' => $goods_height],
            'common' => ['id' => "E4019F635D6CBC488C42C0A8B05F0249", 'path' => 'common/'],
        ];
        $scenes = [];
        foreach ($pages as $page) {
            $scenes [] = $this->createScene($page, false);
        }
        $app_data['scenes'] = $scenes;

        return $this->render('perview', ['app_data' => $app_data]);
    }

    /**
     * 预览
     * @param string $page_ids id,id,id 显示预览的页面id
     * @param string $target 显示预览的目标属性 有 source 和 finish
     *
     * @return type
     */
    public function actionPreview($page_ids, $target = 'finish')
    {
        $pages = OrderGoodsScenePage::find()
            ->where(['id' => explode(',', $page_ids)])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        /** @var GoodsScenePage $page */
        $page = $pages[0];
        $goods_params = json_decode($page->scene->goods->params, true);
        $goods_width = isset($goods_params['width']) ? $goods_params['width'] : 1080;
        $goods_height = isset($goods_params['height']) ? $goods_params['height'] : 1778;
        $app_data = [
            'goods' => ['gid' => $page->scene->goods_id, 'orientation' => $page->scene->goods->orientation, 'width' => $goods_width, 'height' => $goods_height],
            'common' => ['id' => "E4019F635D6CBC488C42C0A8B05F0249", 'path' => 'common/'],
        ];
        $scenes = [];
        foreach ($pages as $page) {
            $scenes [] = $this->createScene($page, $target == 'finish');
        }
        $app_data['scenes'] = $scenes;

        return $this->render('perview', ['app_data' => $app_data]);
    }

    /**
     * 生成绘本页数据
     * @param OrderGoodsScenePage|GoodsScenePage $page
     * @param bool $replace
     */
    private function createScene($page, $replace = true)
    {
        $source_id = $page instanceof OrderGoodsScenePage ? $page->sourcePage->source_id : $page->source_id;//$page->source_id;//
        $source_url = $page instanceof OrderGoodsScenePage ? $page->sourcePage->source_url : $page->source_url;//$page->source_url;//
        // 当前finish_id 为空时，为上传图片，这时使用模板加载上传的图合成绘本页
        $id = (!$replace || $page->finish_id == '') ? $source_id : $page->finish_id;
        $path = (!$replace || $page->finish_id == '') ? $source_url : $page->finish_url;
        return [
            'id' => $id,
            'path' => $this->replaceHttps(pathinfo($path, PATHINFO_DIRNAME) . '/'),
            'lock' => false,
            'replace' => ($replace && $page->finish_id == '') ? [['id' => 'bg', 'url' => $page->finish_url]] : false,
        ];
    }

    private function replaceHttps($path)
    {
        $host_info = \Yii::$app->request->getHostInfo();
        if (strpos($host_info, 'http://') === false) {
            return str_replace('http://', 'https://', $path);
        }
        return $path;
    }

}
