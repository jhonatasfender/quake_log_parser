<?php 

namespace App\Models;

use App\Models\Connection;

class Kills extends Connection {

	public $id_kills;
	public $name;
	public $id_players;
	public $total;

	protected $object = "\App\Models\Kills";
	protected $table = "kills";
	protected $key = "id_kills";

	public function __construct() {
		parent::__construct();
		$this->execute();
	}

	private function execute() {
		$this->conn->query("
			CREATE TABLE IF NOT EXISTS quake_log_parser.kills (
				id_kills 	INT(11) NOT NULL AUTO_INCREMENT,
				name 		VARCHAR(45) NOT NULL,
				id_players 	INT(11) NOT NULL,
				total 		VARCHAR(45) NOT NULL,

				PRIMARY KEY (id_kills),
					INDEX fk_kills_players_idx (id_players ASC),
					
				CONSTRAINT fk_kills_players
					FOREIGN KEY (id_players)
					REFERENCES quake_log_parser.players (id_players)
						ON DELETE NO ACTION
						ON UPDATE NO ACTION
			) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
		");
	}

	public function save() {
		$this->columns = [
			'name' => $this->name,
			'id_players' => $this->id_players,
			'total' => $this->total
		];
		if($this->find(['name' => $this->name, 'id_players' => $this->id_players])) {
			$this->update($this->id_kills);
			return $this;
		} else {
			$this->id_kills = parent::insert();
			return $this;
		}
	}

}