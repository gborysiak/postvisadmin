<?php

require_once PROJECT_ROOT_PATH . "/api/Model/Database.php";
require_once PROJECT_ROOT_PATH . "functions.inc.php";

class BlacklistModel extends Database
{
    public function getBlacklist($limit)
    {
        return $this->select("SELECT wblist.rid, wblist.sid, wblist.wb, mailaddr.email as sender, users.email as recipient FROM wblist LEFT JOIN mailaddr ON wblist.sid = mailaddr.id LEFT JOIN users ON wblist.rid = users.id WHERE wblist.wb = 'B' LIMIT ?", ["i", $limit]);
    }
	
	public function postBlacklist($email) 
	{
		return addlist('1', $email, 'B');
	}
}