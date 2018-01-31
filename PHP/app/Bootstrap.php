<?php 
namespace App;

use App\Models\KillsByMeans;
use App\Models\Kills;
use App\Models\Players;
use App\Models\Game;
use App\Models\Dados;
use App\Library\ReaderLog;
use App\Library\Migration;

class Bootstrap {

	public $game;
	public $killsByMeans;
	public $kills;
	public $players;
	public $dados;

	private $count = 0;
	private $k;
	
	private $out;
	private $read;
	private $migration;

	public function __construct() {
		try {
			$this->game = new Game();
			if(!$this->game->findAll()) {
				$this->read = new ReaderLog();
				$this->migration = new Migration($this->read->init());
			}
		} catch (\Exception $e) {
			
		}
	}

	public function get($search = null) {
		$this->game = new Game();
		$this->count = $this->game->count($search);
		return $this->count ? $this->count : null;
	}

	
}