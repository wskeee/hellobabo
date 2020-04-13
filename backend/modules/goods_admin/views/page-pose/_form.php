<?php

use common\utils\I18NUitl;
use common\widgets\webuploader\ImagePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsPagePose */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .point-box {
        position: absolute;
    }

    .point {
        position: absolute;
        width: 5px;
        height: 5px;
        border-radius: 5px;
        border: solid 1px #ff0000;
    }

    .box-label {
        margin-top: 40px;
        padding: 10px 0;
        font-weight: bold;
        font-size: 15px;
        border-top: solid 1px #eee;
    }

    .required-box {
        margin-bottom: 40px;
    }

    .required-box .cb {
        display: inline-block;
        margin-right: 30px;
    }
</style>

<div class="goods-page-pose-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'onsubmit' => "return submitCheck()",
        ]
    ]); ?>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="wskModalLabel"><?= I18NUitl::t('app', '{Add}{Material}') ?></h4>
            </div>

            <div class="modal-body" id="wskModalBody">
                <?= Html::activeHiddenInput($model, 'page_id') ?>
                <?= Html::activeHiddenInput($model, 'pose_data') ?>
                <?= Html::activeHiddenInput($model, 'required_data') ?>
                <!-- 数据源 -->
                <?= $form->field($model, 'filepath')->widget(ImagePicker::class, [
                    'pluginEvents' => [
                        "fileDequeued" => "fileDequeued",
                        'uploadComplete' => "uploadComplete",
                    ]
                ]) ?>

                <!-- 人体数据 -->
                <p class="box-label">人体各点显示：</p>
                <div class="pose-box">
                    <div class="point-box"></div>
                    <?= Html::img($model->filepath?"{$model->filepath}?x-oss-process=image/resize,m_lfit,h_720,w_720":"", ['class' => 'pose-img']) ?>
                </div>

                <!-- 需求数据 -->
                <p class="box-label">请选择需要用来判断得分的人体部位：</p>
                <div class="required-box">

                </div>

            </div>

            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    var pose_data = <?= $model->pose_data ? $model->pose_data : 'null' ?>;
    var required_data = <?= $model->required_data ? $model->required_data : 'null' ?>;

    console.log(pose_data, required_data);
    $(function () {
        renderData();
    });

    /**
     * 更新数据显示
     **/
    function renderData() {
        if (pose_data) {
            // 显示人体点
            renderPoint(pose_data['person_info'][0]['body_parts']);
            // 显示判断规则
            renderRequired(required_data);
        }
    }

    /**
     * 清除源文件
     *
     * @param {type} evt
     * @param {type} file
     * @returns {undefined}
     */
    function fileDequeued(evt, file) {
        pose_data = null;
        required_data = null;
        $('#goodspagepose-filepath').val('');
        $('.pose-img').attr('src', '');
        $('.point-box').empty();
        $('.required-box').empty();
    }

    /**
     * 源文件上传完成
     *
     * @param {type} evt
     * @param {type} dbFile
     * @param {type} file
     * @returns {undefined}
     */
    function uploadComplete(evt, dbFile, file) {
        var filepath = dbFile.url + '?x-oss-process=image/resize,m_lfit,h_720,w_720';
        $('.pose-img').attr('src', filepath);
        getPose(filepath);
    }

    /**
     * 获取 Pose 数据
     */
    function getPose(filepath) {
        $.get('/goods_admin/page-pose/get-pose', {filepath: filepath}, function (r) {
            if (r.code == 0) {
                pose_data = r.data.pose_data;
                required_data = r.data.pose_required;
                renderData();
            } else {
                alert('获取数据出错!');
            }
        })
    }

    /**
     * 显示人体点分布
     * @param body_parts
     */
    function renderPoint(body_parts) {
        $c = $('.point-box').empty();
        $.each(body_parts, function (key, item) {
            $('<div class="point" style="top:' + item.y + 'px;left:' + item.x + 'px;">' + key + '</div>').appendTo($c);
        });
    }

    /**
     * 显示判断规则
     * @param body_parts
     */
    function renderRequired(body_parts) {
        $c = $('.required-box').empty();
        $.each(body_parts, function (key, item) {
            $input = $('<div class="cb"><input type="checkbox" data-key="' + key + '" >' + item['name'] + '</input></div>').appendTo($c);
            if (item['checked'] == undefined || item['checked'] == true) {
                $input.find('input').prop('checked', true);
            }else{
                $input.find('input').prop('checked', false);
            }
        });
    }

    /**
     * 提交验证
     */
    function submitCheck() {
        $.each($('.required-box input'), function (index, item) {
            $key = $(item).attr('data-key');
            $checked = $(item).prop('checked');
            required_data[$key]['checked'] = $checked ? true : false;
        });
        $('#goodspagepose-pose_data').val(pose_data ? JSON.stringify(pose_data) : '');
        $('#goodspagepose-required_data').val(required_data ? JSON.stringify(required_data) : '');
        return true;
    }
</script>
