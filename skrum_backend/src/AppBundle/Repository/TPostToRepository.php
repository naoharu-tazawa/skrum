<?php

namespace AppBundle\Repository;

/**
 * TPostToリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TPostToRepository extends BaseRepository
{
    /**
     * 指定投稿IDに紐付く投稿先を全て削除
     *
     * @param integer $postId 投稿ID
     * @return void
     */
    public function deletePostTo(int $postId)
    {
        $sql = <<<SQL
        UPDATE t_post_to AS t0_
        SET t0_.deleted_at = NOW()
        WHERE (t0_.post_id = :postId) AND (t0_.deleted_at IS NULL);
SQL;

        $params['postId'] = $postId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
