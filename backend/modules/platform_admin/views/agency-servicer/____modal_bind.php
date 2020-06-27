<?php

use common\models\platform\Issue;
use common\models\User;
use kartik\widgets\Select2;

/** @var int $agency_id */
?>
<div id="bind-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">客户绑定</h4>
            </div>

            <div class="modal-body" id="myModalBody">
                <?= Select2::widget([
                    'id' => 'bind-select',
                    'name' => 'user_id',
                    'data' => User::getAllUser(),
                ]) ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat"
                        onclick="submitBind($(this))">
                    <?= Yii::t('app', 'Confirm') ?>
                </button>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">

    function submitBind($dom) {
        console.log($('#bind-select').val());
        window.webservice.BtnLoader.submit({
            dom: $dom,
            url: '<?= $url ?>',
            data: {user_id: $('#bind-select').val()}
        });
    }

</script>