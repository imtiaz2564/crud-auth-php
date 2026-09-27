<?php
namespace Repository\Interfaces;

use Model\Sport; 

interface SportRepositoryInterface
{
    public function createSport(Sport $sport): int;

    public function getData(int $id = 0): \mysqli_result;

    public function updateSport(Sport $sport): bool;

    public function delete(int $id): bool;
}

?>
