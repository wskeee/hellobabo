<?php

use common\models\platform\Issue;
?>
<div id="issue-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">填写问题原因</h4>
            </div>

            <div class="modal-body" id="myModalBody">
                <textarea id="issue-des" class="form-control" rows="6" placeholder="请输入..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat" onclick="submitAssign($(this),<?= Issue::RESULT_YES ?>)">
                    <?= Yii::t('app', 'Solve') ?>
                </button>
                <button type="button" class="btn btn-danger btn-flat" onclick="submitAssign($(this),<?= Issue::RESULT_NO ?>)">
                    <?= Yii::t('app', 'UnSolve') ?>
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
            data: $.extend({des:$('#issue-des').val(),result:result},getSelecteGoodsId())
        });
    }

</script>