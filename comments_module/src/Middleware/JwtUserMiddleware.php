<?php

namespace wildix\comments_modules\src\Middleware;

class JwtUserMiddleware
{
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $userId = 1; //extract user ID from JWT
        $this->user->setId($userId);
    }
}
