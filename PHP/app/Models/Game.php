<?php 

namespace App\Models;

use App\Models\Connection;

class Game extends Connection {

	public function __construct() {
		parent::__construct();
		$this->execute();
	}

	private function execute() {
		$this->conn->query("
			CREATE TABLE IF NOT EXISTS quake_log_parser.game (
				id_game 	INT(11) NOT NULL AUTO_INCREMENT,
				name 		VARCHAR(45) NOT NULL,
				PRIMARY KEY (id_game)
			) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
		");
	}

}