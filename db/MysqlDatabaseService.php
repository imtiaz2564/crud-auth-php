<?php

namespace DB;

use Model\User;
use Model\Sport;
use DatabaseConfig\DatabaseConfig;
use DB\Interfaces\DatabaseServiceInterface;



class MysqlDatabaseService implements DatabaseServiceInterface
{
    private ?\mysqli $conn = null;

    private string $host;
    private string $dbName;
    private string $user;
    private string $dbPass;


    public function __construct(DatabaseConfig $config)
    {
        $this->host = $config->host;
        $this->user = $config->user;
        $this->dbName = $config->db;
        $this->dbPass = $config->password;
    }


    /*
     * Open database connection
     */
    private function open_db(): void
    {
        $this->conn = new \mysqli(
            $this->host,
            $this->user,
            $this->dbPass,
            $this->dbName
        );

        if ($this->conn->connect_error) {
            throw new \RuntimeException(
                'Database connection failed: '
                . $this->conn->connect_error
            );
        }
    }


    /*
     * Close database connection
     */
    private function close_db(): void
    {
        if ($this->conn !== null) {
            $this->conn->close();
            $this->conn = null;
        }
    }


    /*
     * Login
     */
    public function loginUser(User $user): bool
    {
        try {

            $email = $user->getEmail();
            $password = $user->getPassword();

            $this->open_db();

            $query = $this->conn->prepare(
                "SELECT email, password
                 FROM registration
                 WHERE email = ?"
            );

            $query->bind_param(
                "s",
                $email
            );

            $query->execute();

            $result = $query->get_result();

            $row = $result->fetch_assoc();

            $query->close();

            $this->close_db();

            if (!$row) {
                return false;
            }

            return password_verify(
                $password,
                $row['password']
            );

        } catch (\Throwable $e) {

            $this->close_db();

            throw $e;
        }
    }


    /*
     * Registration
     */
    public function registration(User $user): int
    {
        try {

            $email = $user->getEmail();
            $password = $user->getPassword();

            $this->open_db();

            $query = $this->conn->prepare(
                "INSERT INTO registration
                    (email, password)
                 VALUES (?, ?)"
            );

            $query->bind_param(
                "ss",
                $email,
                $password
            );

            $query->execute();

            $affectedRows = $query->affected_rows;

            $query->close();

            $this->close_db();

            return $affectedRows;

        } catch (\Throwable $e) {

            $this->close_db();

            throw $e;
        }
    }

    
    public function emailExists(string $email): bool
    {
        $this->open_db();

        $query = $this->conn->prepare(
            "SELECT id
            FROM registration
            WHERE email = ?"
        );

        $query->bind_param("s", $email);

        $query->execute();

        $result = $query->get_result();

        $exists = $result->num_rows > 0;

        $query->close();
        $this->close_db();

        return $exists;
    }

    /*
     * Create sport
     */
    public function createSport(Sport $sport): int
    {
        try {

            $title = $sport->getTitle();
            $post = $sport->getPost();
            $image = $sport->getImage();

            $this->open_db();

            $query = $this->conn->prepare(
                "INSERT INTO sports
                    (title, post, image)
                 VALUES (?, ?, ?)"
            );

            $query->bind_param(
                "sss",
                $title,
                $post,
                $image
            );

            $query->execute();

            $affectedRows = $query->affected_rows;

            $query->close();

            $this->close_db();

            return $affectedRows;

        } catch (\Throwable $e) {

            $this->close_db();

            throw $e;
        }
    }


    /*
     * Get all sports or one sport
     *
     * id = 0 → get all sports
     * id > 0 → get one sport
     */
    public function getData(int $id = 0): \mysqli_result
    {
        try {

            $this->open_db();

            if ($id > 0) {

                $query = $this->conn->prepare(
                    "SELECT *
                     FROM sports
                     WHERE id = ?"
                );

                $query->bind_param(
                    "i",
                    $id
                );

            } else {

                $query = $this->conn->prepare(
                    "SELECT *
                     FROM sports"
                );
            }

            $query->execute();

            $result = $query->get_result();

            $query->close();

            $this->close_db();

            return $result;

        } catch (\Throwable $e) {

            $this->close_db();

            throw $e;
        }
    }


    /*
     * Delete sport
     */
    public function deleteData(int $id): bool
    {
        try {

            $this->open_db();

            $query = $this->conn->prepare(
                "DELETE FROM sports
                 WHERE id = ?"
            );

            $query->bind_param(
                "i",
                $id
            );

            $result = $query->execute();

            $query->close();

            $this->close_db();

            return $result;

        } catch (\Throwable $e) {

            $this->close_db();

            throw $e;
        }
    }


    /*
     * Update sport
     */
    public function updateSport(Sport $sport): bool
    {
        try {

            $id = $sport->getId();
            $title = $sport->getTitle();
            $post = $sport->getPost();
            $image = $sport->getImage();

            $this->open_db();

            $query = $this->conn->prepare(
                "UPDATE sports
                 SET title = ?,
                     post = ?,
                     image = ?
                 WHERE id = ?"
            );

            $query->bind_param(
                "sssi",
                $title,
                $post,
                $image,
                $id
            );

            $result = $query->execute();

            $query->close();

            $this->close_db();

            return $result;

        } catch (\Throwable $e) {

            $this->close_db();

            throw $e;
        }
    }
}