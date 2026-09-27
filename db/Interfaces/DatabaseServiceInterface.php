<?php

namespace DB\Interfaces;

use \Model\User;
use \Model\Sport;

interface DatabaseServiceInterface {
    public function loginUser(User $user): bool;

    public function registration(User $user): int;

    public function emailExists(string $email): bool;

    public function createSport(Sport $sport): int;

    public function getData(int $id = 0): \mysqli_result;

    public function updateSport(Sport $sport): bool;

    public function deleteData(int $id): bool;
}
