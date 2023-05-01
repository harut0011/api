<?php

namespace System;
use Modules\User\Controller as User;
use System\Exceptions\ExcAuth;

class BaseController
{
    public array $params = [];
    protected array $post = [];
    protected ?array $user;

    public function __construct()
    {
        $this->user = User::getUser();
        // var_dump($this->user);
    }
    protected function checkAccess(): bool
    {
        return $this->user !== null;
    }
}