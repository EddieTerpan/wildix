<?php

namespace wildix\comments_modules\src\Repository;

class CommentsRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findCommentsTreePaginated(int $page, int $perPage, int $postId): array
    {
        $em = $this->getEntityManager();

        $offset = ($page - 1) * $perPage;

        $query = $em->createQuery('
        SELECT c.comment_id, c.user_id, c.post_id, c.comment_text, c.created_at, c.path, 
        IFNULL(latest.latest_created_at, c.created_at) AS latest_created_at
        FROM wildix\modules\comments\src\Entity\CommentEntity c
        LEFT JOIN (
            SELECT parent_comment_id, MAX(created_at) AS latest_created_at
            FROM wildix\modules\comments\src\Entity\CommentEntity
            GROUP BY parent_comment_id
        ) AS latest ON c.comment_id = latest.parent_comment_id
        WHERE c.post_id = :postId
        ORDER BY IFNULL(latest.latest_created_at, c.created_at) DESC, c.path
    ')
            ->setParameter('postId', $postId)
            ->setFirstResult($offset)
            ->setMaxResults($perPage);

        $comments = $query->getResult();

        return $this->transformCommentsToNested($comments);
    }

    private function transformCommentsToNested(array $comments, $parentId = null): array
    {
        $result = [];

        foreach ($comments as $comment) {
            if (str_starts_with($comment['path'], "{$parentId}/")) {
                $children = $this->transformCommentsToNested($comments, $comment['comment_id']);
                if (!empty($children)) {
                    $comment['children'] = $children;
                }
                $result[] = $comment;
            }
        }

        return $result;
    }
}