<?php

namespace apiend\modules\v1\actions\system;

use apiend\models\Response;
use common\components\aliyuncs\Aliyun;
use Exception;
use Yii;
use yii\base\Action;

/**
 * 商家申请入驻
 */
class Upload extends Action
{

    public function run()
    {
        $webuploaderConfig = Yii::$app->params['webuploader'];
        $key = $webuploaderConfig['savePath'];
        
        $name = $_FILES['file']['name'];
        $pathinfo = pathinfo($name);
        $filename = md5($pathinfo['filename']) . '.' . $pathinfo['extension'];
        $object = "{$key}{$filename}";
        try{
            Aliyun::getOss()->multiuploadFile($object, $_FILES['file']['tmp_name']);
        } catch (Exception $ex) {
            return new Response(Response::CODE_COMMON_UNKNOWN, null, $ex->getMessage());
        }
        
        return new Response(Response::CODE_COMMON_OK, null, ['url' => Aliyun::absolutePath($object)]);
    }

}
