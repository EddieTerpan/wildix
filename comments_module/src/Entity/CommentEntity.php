<?php

namespace wildix\comments_modules\src\Entity;

use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
#[ORM\Table(name: 'commentsModule')]
class CommentEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $commentId;

    #[ORM\Column(type: 'integer')]
    private $parentId;

    #[ORM\Column(type: 'integer')]
    private $userId;

    #[ORM\Column(type: 'integer')]
    private $postId;

    #[ORM\Column(type: 'text')]
    private $commentText;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'string')]
    private $path;

    #[ORM\Column(type: 'boolean')]
    private $isDelete;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private $version;

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setParentId(\DateTime $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getIsDelete(): bool
    {
        return $this->isDelete;
    }

    public function setIsDelete(bool $isDelete): void
    {
        $this->isDelete = $isDelete;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }
}