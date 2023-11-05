<?php

namespace wildix\comments_modules\src\Controller;

use wildix\comments_modules\src\Middleware\JwtUserMiddleware;
use wildix\comments_modules\src\Model\CommentModel;
use wildix\comments_modules\src\Repository\CommentsRepository;

#[Route('/api/v1', name: 'api_v1')]
class CommentsController extends AbstractController
{
    public function __construct(
        CommentModel $commentModel,
        JwtUserMiddleware $jwtUserMiddleware,
        CommentsRepository $commentsRepository,
        EntityManagerInterface $entityManager
    ) {
        //init fields ...
    }

    #[Route('/comment', name: 'create_comment', methods: ['POST'])]
    public function createComment(Request $request): JsonResponse
    {
        //...
        return new JsonResponse(['comment' => $comment->toArray()], 201);
    }

    #[Route('/comment/{commentId}', name: 'get_comment', methods: ['GET'])]
    public function getComment(int $commentId): JsonResponse
    {
        //...
        return new JsonResponse(['comment' => $comment->toArray()], 200);
    }

    #[Route('/comment/{commentId}', name: 'update_comment', methods: ['PUT'])]
    public function updateComment(Request $request, int $commentId): JsonResponse
    {
        //...
        return new JsonResponse(['comment' => $comment->toArray()], 200);
    }

    #[Route('/comment/{commentId}', name: 'delete_comment', methods: ['DELETE'])]
    public function deleteComment(int $commentId): JsonResponse
    {
        //...
        return new JsonResponse(null, 204);
    }

    #[Route('/post/{postId}/comments', name: 'get_post_comments', methods: ['GET'])]
    public function getPostComments(Request $request, int $postId): JsonResponse
    {
        //...
        $comments = $this->commentsRepository->findCommentsTreePaginated($page, $perPage, $postId);
        return new JsonResponse($comments, 200);
    }
}