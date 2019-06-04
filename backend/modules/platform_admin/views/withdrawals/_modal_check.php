<?php

use common\models\platform\Issue;
use common\utils\I18NUitl;
?>
<div id="check-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= I18NUitl::t('app', '{Withdrawals}{Check}') ?></h4>
            </div>

            <div class="modal-body" id="myModalBody">
                <textarea id="check-feedback" class="form-control" rows="6" placeholder="请输入..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat" onclick="submitCheck($(this), 1)">
                    <?= I18NUitl::t('app', '{Check}{Pass}') ?>
                </button>
                <button type="button" class="btn btn-danger btn-flat" onclick="submitCheck($(this), 0)">
                    <?= I18NUitl::t('app', '{Check}{No}{Pass}') ?>
                </button>
            </div>

        </div>
    </div> 
</div>

<script type="text/javascript">

    function submitCheck($dom, result) {
        window.webservice.BtnLoader.submit({
            dom: $dom,
            url: '<?= $url ?>',
            data: $.extend({des: $('#check-feedback').val(), result: result}, getSelecteGoodsId())
        });
    }

</script>