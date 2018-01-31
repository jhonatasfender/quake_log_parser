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

	public function findAll() {
		$this->find = $this->conn->prepare("SELECT * FROM game");
		$this->find->execute();
		return $this->find->fetchAll(\PDO::FETCH_OBJ);
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

	public function count($search) {
		$this->sql = "
			SELECT 	p.name,
					IF(sum(k.total) IS NULL,0,sum(k.total)) AS total
			FROM game AS g
			LEFT JOIN players AS p ON p.id_game = g.id_game
			LEFT JOIN kills AS k ON k.id_players = p.id_players
			" . ( "WHERE p.name LIKE '%$search%' " ) . "
			GROUP BY p.name
			ORDER BY total DESC;
		";
		$this->find = $this->conn->prepare($this->sql);

		$this->find->execute();
		return $this->find->fetchAll(\PDO::FETCH_OBJ);
	}

}