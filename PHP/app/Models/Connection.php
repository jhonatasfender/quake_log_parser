<?php 

namespace App\Models;

class Connection {

	const QUAKELOGPARSER = 'quake_log_parser';
	const INFORMATIONSCHEMA = 'INFORMATION_SCHEMA';

	protected $conn;
	protected $settings;

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

	public function __destruct() {
		$this->conn = null;
	}
}