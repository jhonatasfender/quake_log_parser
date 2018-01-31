<?php 

namespace App\Library;

use App\Models\KillsByMeans;
use App\Models\Kills;
use App\Models\Players;
use App\Models\Game;
use App\Models\Dados;

class Migration {
	
	public $game;
	public $killsByMeans;
	public $kills;
	public $players;
	public $dados;

	public function __construct($r) {

		$this->game = new Game();
		
		/**
		 * aqui vou pecorrer a lista gerada anteriomente
		 */
		foreach ($r as $a => $b) {

			/**
			 * aqui vou gravar no banco o inicio do jogo
			 */
			$this->game->name = $a;
			$this->game->total_kills = $b->total_kills;
			$this->game = $this->game->save();

			/**
			 * aqui vou pecorrer a lista gerada anteriomente dos nomes das mortes  
			 */
			foreach ($b->kills_by_means as $c => $v) {

				/**
				 * aqui vou gravar no banco os tipos de mortes relacionado pelo inicio do jogo
				 */
				$this->killsByMeans = new KillsByMeans();
				$this->killsByMeans->name = $c;
				$this->killsByMeans->id_game = $this->game->id_game;
				$this->killsByMeans->total = $v;
				$this->killsByMeans = $this->killsByMeans->save();
			}

			/**
			 * aqui vou percorrer a lista com os nomes dos jogadores
			 */
			foreach ($b->players as $c) {
				/**
				 * aqui vou gravar no banco os nomes dos jogadores
				 */
				$this->players = new Players();
				$this->players->name = $c;
				$this->players->id_game = $this->game->id_game;
				$this->players = $this->players->save();

				/**
				 * aqui vou percorrer a lista com os jogadores que mataram no jogo e verifico comparando com a lista de players
				 */
				foreach ($b->kills as $d => $e) {
					/**
					 * aqui vou compar os nomes para saber quem matou e inserir no banco de dados
					 */
					if($d == $this->players->name) {
						$this->kills = new Kills();
						$this->kills->name = $d;
						$this->kills->id_players = $this->players->id_players;
						$this->kills->total = $e->total;
						$this->kills = $this->kills->save();

						if(isset($e->dados)) { 
							foreach ($e->dados as $f) {
								$this->dados = new Dados();
								$this->dados->causesDeath = $f->causesDeath;
								$this->dados->died = $f->died;
								$this->dados->killed = $f->killed;
								$this->dados->text = $f->text;
								$this->dados->id_kills = $this->kills->id_kills;
								$this->dados = $this->dados->save();
							}
						}
					}
				}
			}
		}	
	}

}