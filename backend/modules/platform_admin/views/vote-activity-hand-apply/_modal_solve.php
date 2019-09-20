<?php

use common\models\activity\VoteActivityHand;
use common\models\platform\Issue;
?>
<div id="check-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">填写问题原因</h4>
            </div>

            <div class="modal-body" id="myModalBody">
                <textarea id="feedback" class="form-control" rows="6" placeholder="请输入..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat" onclick="submitAssign($(this),<?= VoteActivityHand::CHECK_STATUC_SUCCESS ?>)">
                    <?= Yii::t('app', 'Pass') ?>
                </button>
                <button type="button" class="btn btn-danger btn-flat" onclick="submitAssign($(this),<?= VoteActivityHand::CHECK_STATUS_FAILED ?>)">
                    <?= Yii::t('app', 'Un Pass') ?>
                </button>
            </div>

        </div>
    </div> 
</div>

<script type="text/javascript">

    function submitAssign($dom,result) {
        window.webservice.BtnLoader.submit({
            dom: $dom,
            url: '<?= $url ?>',
            data: $.extend({feedback:$('#feedback').val(),result:result},getSelecteGoodsId())
        });
    }

</script>