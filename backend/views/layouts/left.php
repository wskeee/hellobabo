<?php

use common\models\AdminUserr;
use common\modules\rbac\components\Helper;
use common\widgets\Menu;

/* @var $user AdminUserr */
?>
<aside class="main-sidebar">
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <?php if(!Yii::$app->user->isGuest): ?>
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $user->avatar; ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->nickname ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <?php 
            $menuItems = [['label' => 'Menu Yii2', 'options' => ['class' => 'header']]];
            if(Yii::$app->user->isGuest){
                $menuItems []= ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest];
            }else{
                $menuItems = array_merge($menuItems, [
                    ['label' => '清除缓存', 'icon' => 'eraser', 'url' => ['/system_admin/cache/']],
                    ['label' => '绘本看板', 'icon' => 'eraser', 'url' => ['/order_admin/order-board/index']],
                    [
                        'label' => '系统',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '配置管理', 'icon' => 'circle-o', 'url' => ['/system_admin/config/index'],],
                            ['label' => '日常任务', 'icon' => 'circle-o', 'url' => ['/system_admin/crontab/index'],],
                            ['label' => '数据库备份', 'icon' => 'database', 'url' => ['/system_admin/db-backup/index']],
//                            ['label' => 'redis缓存管理', 'icon' => 'circle-o', 'url' => ['/rediscache_admin/acl/index']],
                            ['label' => '上传文件管理', 'icon' => 'circle-o', 'url' => ['/system_admin/uploadfile/index']]
                        ],
                    ],
                    [
                        'label' => '权限与组织管理',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '用户列表', 'icon' => 'circle-o', 'url' => ['/user_admin/default/index'],],
                            ['label' => '用户角色', 'icon' => 'circle-o', 'url' => ['/rbac/user-role/index'],],
                            ['label' => '角色列表', 'icon' => 'circle-o', 'url' => ['/rbac/role/index'],],
                            ['label' => '权限列表', 'icon' => 'circle-o', 'url' => ['/rbac/permission/index'],],
                            ['label' => '路由列表', 'icon' => 'circle-o', 'url' => ['/rbac/route/index'],],
                            ['label' => '分组列表', 'icon' => 'circle-o', 'url' => ['/rbac/auth-group/index'],],
                        ],
                    ],
                    [
                        'label' => '平台管理',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '添加商品', 'icon' => 'circle-o', 'url' => ['/goods_admin/goods/create']],
                            ['label' => '商品列表', 'icon' => 'circle-o', 'url' => ['/goods_admin/goods/index']],
                            ['label' => '商品审核', 'icon' => 'circle-o', 'url' => ['/goods_admin/approve/index']],
                            ['label' => '订单列表', 'icon' => 'circle-o', 'url' => ['/order_admin/default/index']],
                            ['label' => '绘本审核', 'icon' => 'circle-o', 'url' => ['/order_admin/order-goods/index']],
                            ['label' => '设计', 'icon' => 'circle-o', 'url' => ['/order_admin/design/index']],
                            ['label' => '打印', 'icon' => 'circle-o', 'url' => ['/order_admin/print/index']],
                            ['label' => '发货', 'icon' => 'circle-o', 'url' => ['/order_admin/delivery/index']],
                            ['label' => '优惠卷', 'icon' => 'circle-o', 'url' => ['/order_admin/coupon/index']],
                            ['label' => '产品合作方', 'icon' => 'circle-o', 'url' => ['/platform_admin/shop/index']],
                            ['label' => '提现列表', 'icon' => 'circle-o', 'url' => ['/platform_admin/withdrawals/index']],
                            ['label' => '反馈列表', 'icon' => 'circle-o', 'url' => ['/platform_admin/issue/index']],
                            ['label' => '文章管理', 'icon' => 'circle-o', 'url' => ['/platform_admin/post/index']],
                            // ['label' => '投票活动管理', 'icon' => 'circle-o', 'url' => ['/platform_admin/vote-activity/index']],
                            ['label' => '留言', 'icon' => 'circle-o', 'url' => ['/platform_admin/comment/index']],
                            ['label' => '首页Banner', 'icon' => 'circle-o', 'url' => ['/system_admin/banner/index']],
                            ['label' => '活动管理', 'icon' => 'circle-o', 'url' => ['/platform_admin/activity/index']],
                        ],
                    ],
                    [
                        'label' => '平台配置',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '商品分类', 'icon' => 'circle-o', 'url' => ['/platform_config/category/index']],
                            ['label' => '商品模型', 'icon' => 'circle-o', 'url' => ['/platform_config/goods-model/index']],
                            ['label' => '场景分组', 'icon' => 'circle-o', 'url' => ['/platform_config/scene-group/index']],
                            ['label' => '拍摄角度', 'icon' => 'circle-o', 'url' => ['/platform_config/shooting-angle/index']],
                            ['label' => '拍摄表情', 'icon' => 'circle-o', 'url' => ['/platform_config/shooting-face/index']],
                            ['label' => '快递公司', 'icon' => 'circle-o', 'url' => ['/platform_config/express/index']],
                            ['label' => '文章类型', 'icon' => 'circle-o', 'url' => ['/platform_config/post-type/index']],
                        ],
                    ],
                    [
                        'label' => '代理商',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '代理商列表', 'icon' => 'circle-o', 'url' => ['/platform_admin/agency/index']],
                            ['label' => '订单列表', 'icon' => 'circle-o', 'url' => ['/platform_admin/agency-order/index']],
                        ],
                    ],
                    [
                        'label' => '统计',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '总统计', 'icon' => 'circle-o', 'url' => ['/statistics/all-statistics/index']],
                            ['label' => '排行统计', 'icon' => 'circle-o', 'url' => ['/statistics/ranking-statistics/index']],
                            ['label' => '单独统计', 'icon' => 'circle-o', 'url' => ['/statistics/single-statistics/index']],
                        ]
                    ],
                ]);
                
                foreach($menuItems as &$menuItem){
                    if(isset($menuItem['items'])){
                        $visible = false;
                        foreach($menuItem['items'] as &$item){
                            $item['visible'] = Helper::checkRoute($item['url'][0]);
                            if($item['visible']){
                                $visible = true;
                            }
                        }
                        unset($item);
                        $menuItem['visible'] = $visible;
                    }else if(isset($menuItem['url'])){
                        $menuItem['visible'] = Helper::checkRoute($menuItem['url'][0]);;
                    }
                }
            }
        ?>
        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree  sidebar-open sidebar-mini', 'data-widget'=> 'tree'],
                'items' => $menuItems,
            ]
        ); ?>

    </section>

</aside>
