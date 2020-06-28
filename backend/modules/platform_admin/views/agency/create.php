<?php

use common\utils\I18NUitl;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\platform\Agency */

$this->title = I18NUitl::t('app', '{Create}{Agency}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-create">

    <div class="alert alert-danger" role="alert">
        创建代理商流程：<br/>
        1、新建后台账号，选择账号类型为代理商<br/>
        2、把新建的账号加入代理商角色组<br/>
        3、新建代理商，选择管理员为上一步创建的账号<br/>
        4、绑定客服<br/>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
