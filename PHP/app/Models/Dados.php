<?php 

namespace App\Models;

use App\Models\Connection;

class Dados extends Connection {

	public $id_dados;
	public $died;
	public $causes_death;
	public $killed;
	public $text;
	public $id_kills;

	protected $object = "\App\Models\Dados";
	protected $table = "dados";
	protected $key = "id_dados";

	public function __construct() {
		parent::__construct();
		$this->execute();
	}

	private function execute() {
		$this->conn->query("
			CREATE TABLE IF NOT EXISTS quake_log_parser.dados (
				id_dados 		INT(11) NOT NULL AUTO_INCREMENT,
				died 			VARCHAR(45) NULL DEFAULT NULL,
				causes_death 	VARCHAR(45) NULL DEFAULT NULL,
				killed 			VARCHAR(45) NULL DEFAULT NULL,
				text 			VARCHAR(45) NULL DEFAULT NULL,
				id_kills 		INT(11) NULL DEFAULT NULL,

				PRIMARY KEY (id_dados),
					INDEX fk_dados_kills_idx (id_kills ASC),

				CONSTRAINT fk_dados_kills
					FOREIGN KEY (id_kills)
					REFERENCES quake_log_parser.kills (id_kills)
						ON DELETE NO ACTION
						ON UPDATE NO ACTION
			) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
		");
	}

	public function save() {
		$this->columns = [
			'died' => $this->died,
			'causes_death' => $this->causes_death,
			'killed' => $this->killed,
			'text' => $this->text,
			'id_kills' => $this->id_kills
		];

		if($this->find(['name' => $this->name, 'id_kills' => $this->id_kills])) {
			$this->update($this->id_dados);
			return $this;
		} else {
			$this->id_dados = parent::insert();
			return $this;
		}
	}

}