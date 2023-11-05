<?php

namespace wildix\comments_modules\src\Model;

use wildix\comments_modules\src\Entity\CommentEntity;

class CommentModel
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
    }

    public function createComment(string $text, int $userId, int $postId, int $parentId): CommentEntity
    {
    }

    public function getCommentById(int $commentId): ?CommentEntity
    {
    }

    public function updateComment(CommentEntity $comment)
    {
    }

    public function deleteComment(CommentEntity $comment)
    {
    }
}
