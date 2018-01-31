<?php 

namespace App\Models;

class Connection {

	const QUAKELOGPARSER = 'quake_log_parser';
	const INFORMATIONSCHEMA = 'INFORMATION_SCHEMA';

	protected $conn;
	protected $settings;

	protected $object;
	protected $table;
	protected $columns;
	protected $key;

	private $find;
	private $ins;
	private $fields;
	private $sql;

	public function __construct() {
		try {
			$this->createDatabase();

			$this->createConnection(self::QUAKELOGPARSER);

		} catch (\PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}

	private function createConnection($t) {
		$this->conn = new \PDO($this->drive($t), $this->getUser(), $this->getPassword());		
	}

	private function createDatabase() {
		$this->settings = (object) require __DIR__ . '/../../src/settings.php';

		$this->createConnection(self::INFORMATIONSCHEMA);

		$this->conn->query('CREATE SCHEMA IF NOT EXISTS ' . self::QUAKELOGPARSER . ' DEFAULT CHARACTER SET utf8 ');
		
		$this->conn = null;
	}

	private function drive($t) {
		return 'mysql:host=' . $this->getHost() . ';dbname=' . $t;
	}

	protected function getHost() {
		return $this->settings->connection['host'];
	}

	protected function getUser() {
		return $this->settings->connection['user'];
	}

	protected function getPassword() {
		return $this->settings->connection['password'];
	}

	public function find($c,$v = null) {
		if(is_array($c)) {
			foreach ($c as $field => $v) 
				$this->ins .= "$field = :$field AND ";
			$this->ins = substr($this->ins,0,strlen($this->ins) - 5);
			$this->sql = "SELECT * FROM {$this->table} WHERE $this->ins";
		} else
			$this->sql = "SELECT * FROM {$this->table} WHERE {$c} = :id";

		$this->find = $this->conn->prepare($this->sql);

		if(is_array($c))
			foreach ($c as $f => $v) 
				$this->find->bindParam(":$f", $v);
		else
			$this->find->bindParam(":id", $v);

		$this->find->execute();
		
		return $this->find->fetchObject($this->object);
	}

	protected function update($id) {
		$this->ins = "";
		foreach ($this->columns as $field => $v) 
			$this->ins .= "$field = :$field";

		$this->sql = "UPDATE $this->table SET $this->ins WHERE $this->key = :$this->key";

		$this->sth = $this->conn->prepare($this->sql);
		
		foreach ($this->columns as $f => $v)
			$this->sth->bindValue(':' . $f, $v);

		$this->sth->bindValue(":$this->key", $id);

		$this->sth->execute();
	}

	protected function insert() {
		$this->ins = [];
		foreach ($this->columns as $field => $v) 
			$this->ins[] = ":$field";
		$this->ins = implode(',', $this->ins);
		$this->fields = implode(',', array_keys($this->columns));
		$this->sql = "INSERT INTO $this->table ($this->fields) VALUES ($this->ins)";

		$this->sth = $this->conn->prepare($this->sql);
		
		foreach ($this->columns as $f => $v)
			$this->sth->bindValue(':' . $f, $v);

		$this->sth->execute();
		return $this->conn->lastInsertId();
	}

	public function __destruct() {
		$this->conn = null;
	}
}