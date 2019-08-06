<?php

namespace frontend\modules\show\controllers;

use common\models\goods\GoodsScene;
use common\models\goods\GoodsScenePage;
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
    public function actionPg()
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
                    'Scene.goods_id' => 2,
                    'Scene.is_selected' => 1,
                    'Scene.is_del' => 0,
                    'Page.is_del' => 0,
                ])
                ->orderBy([
            'scene_sort_order' => SORT_ASC,
            'page_pos' => SORT_ASC,
        ]);

        $pages = $query->all();

        /**
         * 
         */
        $app_data = [
            'common' => ['id' => "E4019F635D6CBC488C42C0A8B05F0249", 'path' => 'common/'],
        ];
        $scenes = [];
        foreach ($pages as $page) {
            $scenes [] = [
                'id' => $page['source_id'],
                'path' => pathinfo($page['source_url'], PATHINFO_DIRNAME).'/',
                'lock' => false
            ];
        }
        $app_data['scenes'] = $scenes;
        
        return $this->render('pg',['app_data' => $app_data]);
    }

    /**
     * 展示用户商品
     */
    public function actionUg()
    {
        
    }

}
