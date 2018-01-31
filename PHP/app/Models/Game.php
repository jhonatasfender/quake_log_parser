<?php 

namespace App\Models;

use App\Models\Connection;

class Game extends Connection {

	public $id_game;
	public $name;
	public $total_kills;

	protected $object = "\App\Models\Game";
	protected $table = "game";
	protected $key = "id_game";

	public function __construct() {
		parent::__construct();
		$this->execute();
	}

	private function execute() {
		$this->conn->query("
			CREATE TABLE IF NOT EXISTS quake_log_parser.game (
				id_game 	INT(11) NOT NULL AUTO_INCREMENT,
				name 		VARCHAR(45) NOT NULL,
				total_kills	INT(11) NOT NULL,
				PRIMARY KEY (id_game)
			) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

		");
	}

	public function save() {
		$this->columns = [
			'name' => $this->name,
			'total_kills' => $this->total_kills
		];

		if($this->find('name', $this->name)) {
			$this->update($this->id_game);
			return $this;
		} else {
			$this->id_game = parent::insert();
			return $this;
		}
	}

	public function count() {
		/*$q = $this->conn->prepare("

		");

		$this->find->execute();
		return $this->find->fetchObject();*/
	}

}