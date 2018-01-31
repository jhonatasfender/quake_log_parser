<?php 

namespace App\Models;

use App\Models\Connection;

class KillsByMeans extends Connection {

	public function __construct() {
		parent::__construct();
		$this->execute();
	}

	private function execute() {
		$this->conn->query("
			CREATE TABLE IF NOT EXISTS quake_log_parser.kills_by_means (
				id_kills_by_means 	INT(11) NOT NULL AUTO_INCREMENT,
				name 				VARCHAR(45) NOT NULL,
				id_game 			INT(11) NOT NULL,

				PRIMARY KEY (id_kills_by_means),
					INDEX fk_kills_by_means_game_idx (id_game ASC),

				CONSTRAINT fk_kills_by_means_game
					FOREIGN KEY (id_game)
					REFERENCES quake_log_parser.game (id_game)
						ON DELETE NO ACTION
						ON UPDATE NO ACTION
			) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
		");
	}

}