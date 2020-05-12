<?php


namespace common\utils;


use common\components\aip_php_sdk\BaiduApi;
use Imagine\Image\ManipulatorInterface;
use phpDocumentor\Reflection\Types\Self_;
use yii\imagine\Image;
use yii\web\HttpException;

class PoseUtil
{
    /**
     * 分析得分
     *
     * @param string $filepath 图片路径
     * @param array $pose 目标pose 创形
     *
     * @return array
     */
    public static function check($filepath, $pose)
    {
        $res = self::getPose($filepath);
        if(isset($res['error_code']) && $res['error_code'] > 0){
            throw new \Exception($res['error_msg']);
        }else if(count($res['person_info']) == 0){
            return [
                'avg_score' => 0
            ];
        }
        $d = $res['person_info'][0]['body_parts'];

        $all_score = 0;
        foreach ($pose as $key => $data) {
            $score = self::_check($d, $key, $data);
            $all_score += $score;
        }
        $avg_score = round($all_score / count($pose) * 100);

        return [
            'avg_score' => $avg_score
        ];
    }

    /**
     * 获取 pose 创形数据
     * @param string $filepath 文件路径
     * @return array
     */
    public static function getPose($filepath)
    {
        // 获取图片数据
        $image = file_get_contents($filepath);
        // 调用人体关键点识别
        $res = BaiduApi::getClient()->bodyAnalysis($image);

        return $res;
    }

    /**
     * 生成 pose 评分数据
     * @param array $pose_data 百度人体数据
     * @return array
     */
    public static function maskPoseRequired($pose_data)
    {
        // pose tpl 模板
        $pose = [
            'head_neck_angle' => [
                'name' => '头角度',
                'required' => [0, 10],
                'points' => ['top_head', 'nose', 'neck']
            ],

            'shoulder_left_angle' => [
                'name' => '左肩',
                'required' => [0, 10],
                'points' => ['left_hip', 'left_shoulder', 'left_elbow']
            ],
            'arm_left_angle' => [
                'name' => '左手臂',
                'required' => [45, 10],
                'points' => ['left_shoulder', 'left_elbow', 'left_wrist']
            ],

            'shoulder_right_angle' => [
                'name' => '右肩',
                'required' => [0, 10],
                'points' => ['right_hip', 'right_shoulder', 'right_elbow']
            ],
            'arm_right_angle' => [
                'name' => '左手臂',
                'required' => [0, 10],
                'points' => ['right_shoulder', 'right_elbow', 'right_wrist']
            ],

            'hip_left_angle' => [
                'name' => '左大腿',
                'required' => [0, 10],
                'points' => ['left_shoulder', 'left_hip', 'left_knee']
            ],
            'leg_left_angle' => [
                'name' => '左脚',
                'required' => [0, 10],
                'points' => ['left_hip', 'left_knee', 'left_ankle']
            ],

            'hip_right_angle' => [
                'name' => '右大腿',
                'required' => [0, 10],
                'points' => ['right_shoulder', 'right_hip', 'right_knee']
            ],
            'leg_right_angle' => [
                'name' => '右脚',
                'required' => [0, 10],
                'points' => ['right_hip', 'right_knee', 'right_ankle']
            ],
        ];
        $d = $pose_data['person_info'][0]['body_parts'];
        foreach ($pose as $key => &$data) {
            $data['required'][0] = self::getAngle($d,$data['points']);
        }
        return $pose;
    }

    /**
     * 检查角度
     * @param array $d
     * @param string $key
     * @param array $data
     */
    private static function _check($d, $key, $data)
    {
        $points = $data['points'];
        $required = $data['required'];
        list($required_angle, $range) = $required;

        $score = 0;
        $angle = self::getAngle($d,$points);

        // 是否在最佳范围内，如果是即1分，如果偏差在30度内 按偏差大小计分，超过30度分数为0
        $d_angle = abs($angle - $required_angle);
        if ($d_angle <= $range) {
            $score = 1;
        } else if ($d_angle < 30) {
            $score = $d_angle / 30;
        }
        return $score;
    }

    /**
     * 获取三个点所组成的角度
     *
     * @param array $d 人体数据
     * @param array $points
     * @return int
     */
    private static function getAngle($d, $points)
    {
        $angle = 0;
        list($beginkey, $middlekey, $endkey) = $points;
        if (isset($d[$beginkey]) && isset($d[$middlekey]) && isset($d[$endkey])) {
            $angle = self::GetCrossAngle($d[$beginkey], $d[$middlekey], $d[$middlekey], $d[$endkey]);
        }
        return $angle;
    }

    /**
     * 求角度
     * @param array $l1p1 起点 [x,y]
     * @param array $l1p2 中间点 [x,y]
     * @param array $l2p1 中间点 [x,y]
     * @param array $l2p2 终点[x,y]
     *
     */
    private static function GetCrossAngle($l1p1, $l1p2, $l2p1, $l2p2)
    {
        $arr_0 = [($l1p2['x'] - $l1p1['x']), ($l1p2['y'] - $l1p1['y'])];
        $arr_1 = [($l2p2['x'] - $l2p1['x']), ($l2p2['y'] - $l2p1['y'])];
        $cos_value = (floatval(self::dot($arr_0, $arr_1))) / (sqrt(self::dot($arr_0, $arr_0)) * sqrt(self::dot($arr_1, $arr_1)));
        $result = 180 - (acos($cos_value) * (180 / pi()));
        $result = round($result, 1);
        return $result;
    }

    /**
     * 向量积
     * @param array $arr1
     * @param array $arr2
     */
    private static function dot($arr1, $arr2)
    {
        return $arr1[0] * $arr2[0] + $arr1[1] * $arr2[1];
    }

    /**
     * 创建目录
     * @param string $path
     */
    private static function mkdir($path)
    {
        if (!file_exists($path)) {
            if (!(@mkdir($path, 0777, true))) {
                throw new HttpException(500, '创建目录失败');
            }
        }
    }
}