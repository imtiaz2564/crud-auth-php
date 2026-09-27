<?php

namespace Repository\Interfaces;

use Model\User;

interface LoginRepositoryInterface
{
    public function loginUser(User $user): bool;

    public function registration(User $user): int;

    public function emailExists(string $email): bool;
}
