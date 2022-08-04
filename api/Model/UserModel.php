<?php
require_once PROJECT_ROOT_PATH . "/api/Model/Database.php";

class UserModel extends Database
{
    public function getUsers($limit)
    {
        return $this->select("SELECT username FROM mailbox ORDER BY 1 ASC LIMIT ?", ["i", $limit]);
    }
}