<?php 

namespace App\Models;

use App\Models\Connection;

class Players extends Connection {

	public $id_players;
	public $name;
	public $id_game;

	protected $object = "\App\Models\Players";
	protected $table = "players";
	protected $key = "id_players";

	public function __construct() {
		parent::__construct();
		$this->execute();
	}

	private function execute() {
		$this->conn->query("
			CREATE TABLE IF NOT EXISTS quake_log_parser.players (
				id_players 	INT(11) NOT NULL AUTO_INCREMENT,
				name 		VARCHAR(45) NOT NULL,
				id_game 	INT(11) NOT NULL,

				PRIMARY KEY (id_players),
					INDEX fk_players_game_idx (id_game ASC),
				
				CONSTRAINT fk_players_game
					FOREIGN KEY (id_game)
					REFERENCES quake_log_parser.game (id_game)
						ON DELETE NO ACTION
						ON UPDATE NO ACTION
			) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
		");
	}

	public function save() {
		$this->columns = [
			'name' => $this->name,
			'id_game' => $this->id_game
		];

		if($this->find(['name' => $this->name, 'id_game' => $this->id_game])) {
			d(['name' => $this->name, 'id_game' => $this->id_game]);
			$this->update($this->id_players);
			return $this;
		} else {
			$this->id_players = parent::insert();
			return $this;
		}
	}

}