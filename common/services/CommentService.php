<?php


namespace common\services;


use common\models\platform\Comment;

class CommentService
{
    /**
     * 添加留言
     * @param string $title 标题
     * @param string $content 内容
     * @param int $created_by 用户
     * @param int $is_hide 匿名
     * @param int $pid 父级
     * @param int $related_id 相关ID
     * @return Comment|null
     */
    public static function add($title, $content, $created_by, $is_hide = 0, $pid = 0, $related_id = 0)
    {
        $parent = null;
        $depth = 0;
        $thread = '';
        if (!empty($pid)) {
            $parent = self::get($pid);
            $depth = $parent->depth + 1;
            $thread = $parent->thread == '' ? $pid : "{$parent->thread},$pid";
        }

        $model = new Comment([
            'pid' => $pid,
            'related_id' => $related_id,
            'title' => $title,
            'content' => $content,
            'depth' => $depth,
            'thread' => $thread,
            'created_by' => $created_by,
            'is_hide' => $is_hide,
        ]);

        if ($model->save()) {
            return $model;
        }
        return null;
    }

    /**
     * 获取留言分页
     * @param array $params
     * @param int $page
     * @param int $limit
     * @param string $sort_order
     * @return array
     */
    public static function getList($params, $page = 1, $limit = 0, $sort_order = 'created_at desc')
    {
        $page <= 0 && $page = 1;
        $query = Comment::find()->where($params)->where('is_del', 0);
        $total = $query->count();

        if ($limit != 0) {
            $query->offset($limit * ($page - 1))->limit($limit);
        }
        $query->orderBy($sort_order);
        $list = $query->with('creator')->all();

        return [
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'list' => $list
        ];

    }

    /**
     * 获取指定时间内，留言条数
     * @param int $created_by
     * @param int $start_time
     * @param int $end_time
     * @return int|string
     */
    public static function getCommentNumByDate($created_by, $start_time, $end_time)
    {
        return Comment::find()
            ->where(['created_by' => $created_by,])
            ->andFilterWhere(['>=', 'created_at', $start_time])
            ->andFilterWhere(['<=', 'created_at', $end_time])
            ->count();
    }

    /**
     * 获取留言
     * @param int $id
     * @return Comment|null
     */
    public static function get($id)
    {
        return Comment::findOne(['id' => $id]);
    }
}