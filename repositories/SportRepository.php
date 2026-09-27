<?php

namespace Repository;

use Model\Sport;
use DB\Interfaces\DatabaseServiceInterface;
use Repository\Interfaces\SportRepositoryInterface;



class SportRepository implements SportRepositoryInterface
{
    private DatabaseServiceInterface $db;
    public function __construct(DatabaseServiceInterface $db)
    {
        $this->db = $db;

    }
    public function createSport(Sport $sport) : int
    {
        return $this->db->createSport($sport);

    }
    public function getData(int $id = 0): \mysqli_result
    {
        return $this->db->getData($id);
    }
    public function delete(int $id) : bool
    {
        return $this->db->deleteData($id);
    }
    public function updateSport(Sport $sport) : bool
    {
        return $this->db->updateSport($sport);
    }
}


?>
