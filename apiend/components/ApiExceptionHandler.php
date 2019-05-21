<?php

namespace apiend\components;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\ErrorHandler;
use yii\web\HttpException;

/**
 * Description of ApiExceptionHandler
 *
 * @author Administrator
 */
class ApiExceptionHandler extends ErrorHandler
{

    public function renderException($exception)
    {
        if (YII_DEBUG) {
            // 如果为开发模式时，可以按照之前的页面渲染异常，因为框架的异常渲染十分详细，方便我们寻找错误
            return parent::renderException($exception);
        } else {
            echo json_encode($this->convertExceptionToArray($exception));
        }
    }

    /**
     * 将异常转换为array输出
     * @see \yii\web\ErrorHandle
     * @param Exception $exception
     * @return multitype:string NULL Ambigous <string, \yii\base\string> \yii\web\integer \yii\db\array multitype:string NULL Ambigous <string, \yii\base\string> \yii\web\integer \yii\db\array
     */
    protected function convertExceptionToArray($exception)
    {
        if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
            $exception = new HttpException(500, Yii::t('yii', 'An internal server error occurred.'));
        }
        $response = [
            'msg' => $exception->getMessage(),
            'code' => property_exists($exception, 'statusCode') ? (string) $exception->statusCode : "500",
        ];
        $array = [
            'name' => ($exception instanceof Exception2 || $exception instanceof ErrorException) ? $exception->getName() : 'Exception',
        ];
        if ($exception instanceof HttpException) {
            $array['status'] = property_exists($exception, 'statusCode') ? (string) $exception->statusCode : "500";
        }
        if (YII_DEBUG) {
            $array['type'] = get_class($exception);
            if (!$exception instanceof UserException) {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
                $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof Exception2) {
                    $array['error-info'] = $exception->errorInfo;
                }
            }
        }
        if (($prev = $exception->getPrevious()) !== null) {
            $array['previous'] = $this->convertExceptionToArray($prev);
        }
        $response['data'] = $array;
        return $response;
    }

}
