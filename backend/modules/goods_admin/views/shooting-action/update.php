<?php

use common\models\goods\ShootingAction;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ShootingAction */

$this->title = I18NUitl::t('app', '{Update} {Shooting Action}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Scene Pages'), 'url' => ['/goods_admin/scene-page/index', 'scene_id' => $model->page->scene_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shooting Action'), 'url' => ['index', 'page_id' => $model->page_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shooting-action-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
