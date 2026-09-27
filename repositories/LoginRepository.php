<?php

namespace Repository;

use DB\Interfaces\DatabaseServiceInterface;
use Model\User;
use Repository\Interfaces\LoginRepositoryInterface;


class LoginRepository implements LoginRepositoryInterface
{
    private DatabaseServiceInterface $db;

    public function __construct(DatabaseServiceInterface $db)
    {
        $this->db = $db;
    }

    public function loginUser(User $user): bool
    {
        return $this->db->loginUser($user);
    }

    public function registration(User $user): int
    {
        return $this->db->registration($user);
    }

    public function emailExists(string $email): bool
    {
        return $this->db->emailExists($email);
    }
}
