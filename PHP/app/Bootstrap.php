<?php 
namespace App;

use App\Models\KillsByMeans;
use App\Models\Kills;
use App\Models\Players;
use App\Models\Game;
use App\Models\Dados;

class Bootstrap {

	public $game;
	public $killsByMeans;
	public $kills;
	public $players;

	private $out;
	private $count = 0;
	private $k;
	
	public function __construct() {
		$this->out = new \stdClass;
		
		$this->game = new Game();

		foreach (file(__DIR__ . "/log/games.log") as $line) {

			/**
			 * Inicio do jogo
			 */				
			if(is_string($this->startGame($line))) {
				$this->count++;
				$this->out->{'game_' . $this->count} = new \stdClass; 
			}

			if($this->count <> 0) { 
				/**
				 * verifico se existe o total de kills e atribuo 0 para ele
				 */	
				if(!isset($this->out->{'game_' . $this->count}->total_kills))
					$this->out->{'game_' . $this->count}->total_kills = 0;

				/**
				 * inicializa a lista de jogador
				 */				
				if(!is_array($this->out->{'game_' . $this->count}->players))
					$this->out->{'game_' . $this->count}->players = [];

				/**
				 * Atribuo a lista os usuarios do jogo atual
				 * por que eu coloquei o key com o nome do jogador, para poder facilitar que existise somente um na lista
				 * com isso mantenho a integridade da lista
				 */
				if($this->playerName($this->playerInfo($line)))
					$this->out->{'game_' . $this->count}->players[$this->playerName($this->playerInfo($line))] = $this->playerName($this->playerInfo($line));

				/**
				 * Inicializo a lista de mortos
				 */
				if(!isset($this->out->{'game_' . $this->count}->kills))
					$this->out->{'game_' . $this->count}->kills = new \stdClass;

				/**
				 * Inicializo a lista de motivo da morte
				 */
				if(!isset($this->out->{'game_' . $this->count}->kills_by_means))
					$this->out->{'game_' . $this->count}->kills_by_means = [];

				/**
				 * verifico as ocorrencias de morte
				 */
				if($this->kill($line)) { 
					
					/**
					 * inicializando a lista de mortos por jogador
					 */
					if(!isset($this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))}))
						$this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))} = new \stdClass;
					
					/**
					 * depois de inicializado a lista de mortos por jogador verifico se não existe o total e atribuo 0 para o total por usuario
					 */
					if(!isset($this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))}->total))
						$this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))}->total = 0;

					/**
					 * Aqui verifico se foi o <world> quem matou
					 */
					if($this->killed($this->kill($line)) == "<world>") {

						/**
						 * Aqui eu inicializo o usuario morto caso não exista na lista criando anterio, faço isso de precaução
						 */
						if(!isset($this->out->{'game_' . $this->count}->kills->{$this->died($this->kill($line))}))
							$this->out->{'game_' . $this->count}->kills->{$this->died($this->kill($line))} = new \stdClass;

						/**
						 *  verifico o total se não existe
						 */
						if(!isset($this->out->{'game_' . $this->count}->kills->{$this->died($this->kill($line))}->total))
							$this->out->{'game_' . $this->count}->kills->{$this->died($this->kill($line))}->total = 0;							

						/**
						 * Subtraio o valor do jogador que foi morto pelo <world>
						 */
						$this->out->{'game_' . $this->count}->kills->{$this->died($this->kill($line))}->total--;

					} else {
						/**
						 * aqui eu somo mais um para o jogador que for diferente di <world> e que matou alguem
						 * atenção se o jogador estava com 0 e é morto pelo <world> ele vai para -1 caso depois ele mate alguem sua pontuação será 
						 * somada mais 1 e voltarar para o 0, caso ele mate outro jogador novamente sua pontuação será somada e subirar para 1
						 */
						$this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))}->total++;
					}

					/**
					 * Nessa parte eu inicializo os dados só mente para manter o controle e saber quem o jogador matou
					 */
					if(!is_array($this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))}->dados)) 
						$this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))}->dados = [];
					
					/**
					 * Inicializando os dados 
					 * $s->text : esse atributo e mais para poder ver o texto que foi extraido da expressão regular
					 * 
					 * $s->killed : esse atributo é para poder saber o nome do jogador que matou alguem
					 * $s->died : esse atributo é para poder saber que o jogador matou 
					 * $s->causesDeath : e nesse atributo eu sei qual foi a causa da morte de cada um que foi morto por esse jogador
					 */
					$s = new \stdClass;
					$s->text = $this->kill($line);
					$s->killed = $this->killed($this->kill($line));
					$s->died = $this->died($this->kill($line));
					$s->causesDeath = $this->causesDeath($this->kill($line));

					/**
					 * atribuindo o valor da soma de todas as formas de morte no jogo
					 */
					$this->out->{'game_' . $this->count}->total_kills++;

					/**
					 * atribuindo a listas todas as causas da mortes que acontecer conforme o key gerado e assim vou somando
					 */
					$this->out->{'game_' . $this->count}->kills_by_means[$s->causesDeath]++;

					/**
					 * atribuindo o $s a array
					 */
					$this->out->{'game_' . $this->count}->kills->{$this->killed($this->kill($line))}->dados[] = $s;
				}
			}
		}

		/**
		 * aqui vou pecorrer a lista gerada anteriomente
		 */
		foreach ($this->out as $a => $b) {

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
						d($e);
						$this->kills = new Kills();
						$this->kills->name = $d;
						$this->kills->id_players = $this->players->id_players;
						$this->kills->total = $e->total;
						$this->kills = $this->kills->save();
					}
				}
			}
		}
	}

	public function get() {
		$this->game = new Game();
		d($this->game->count());
	}

	private function startGame($i) { 
		return preg_grep("/InitGame/", [$i])[0];
	}

	private function endGame($i) { 
		return preg_grep("/--------+/", [$i])[0];
	}

	/**
	 * captura a string toda das possiveis mortes
	 */
	private function kill($i) { 
		// return preg_grep("/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/", [$i])[0];
		preg_match("/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/", $i, $out);
		return $out[0];
	}

	/**
	 * capturar o quem matou, com base no kill
	 */
	private function killed($i) { 
		preg_match("/(?<=:\s)(.*?)(?=\skilled)/", $i, $out);
		return $out[0];
	}

	/**
	 * capturar o quem morreu, com base no kill
	 */
	private function died($i) { 
		preg_match("/(?<=killed\s)(.*?)(?=\sby)/", $i, $out);
		return $out[0];
	}

	private function causesDeath($i) { 
		preg_match("/(?<=by\s)(.*?)(?=$)/", $i, $out);
		return $out[0];
	}

	private function playerInfo($i) {
		return preg_grep("/ClientUserinfoChanged: \d n\\\\(.*?)\\\\/", [$i])[0];
	}

	private function playerName($i) { 
		preg_match("/(?<=\\\\)(.*?)(?=\\\\)/", $i, $out);
		return $out[0];
	}
}