<?php

use backend\modules\statistics\assets\StatisticsModuleAsset;
use common\widgets\charts\ChartAsset;
use yii\web\View;

/* @var $this View */

StatisticsModuleAsset::register($this);
ChartAsset::register($this);

$this->title = Yii::t('app', 'Total Statistics');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php

?>