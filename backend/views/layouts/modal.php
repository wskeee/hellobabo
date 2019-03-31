<?php

//use common\widgets\ueditor\UeditorAsset;

/** 模态框 ///加载富文本编辑器 */
    
//UeditorAsset::register($this);

?>

<div class="modal fade myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!--
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">提示消息</h4>
            </div>
            
            <div class="modal-body" id="myModalBody">内容</div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" aria-label="Close">关闭</button>
            </div>
            -->
       </div>
    </div> 
</div>

<script type="text/javascript">
    /**
     * 显示模态框，加载url内容 
     * @param {string} url
     * @returns {Modal}
     */
    function showModalByUrl(url){
        return $('.myModal').html("").modal("show").load(url);
    }
    
    /**
     * 显示模态框
     * @param {type} dom
     * @returns {Modal}
     */
    function showModalByDom(dom){
        return $('.myModal').html(dom).modal("show");
    }
    
    /**
     * 隐藏模态框
     * @returns {Modal}
     */
    function hideModal(){
        return $('.myModal').html("").modal("hide");
    }
    
</script>