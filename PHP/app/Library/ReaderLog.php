<?php 

namespace App\Library;

use App\Models\Game;
use App\Library\Valid;

class ReaderLog {

	private $out;
	private $line;
	private $count = 0;
	
	public function __construct() {

	}

	private function start() {
		$this->out = new \stdClass;
		$this->game = new Game();
	}

	private function read() {
		return file(__DIR__ . "/../log/games.log");
	}

	/**
	 * Inicio do jogo
	 */
	private function startGame() {
		if(is_string(Valid::startGame($this->line))) {
			$this->count++;
			$this->out->{'game_' . $this->count} = new \stdClass; 
		}
	}

	/**
	 * verifico se existe o total de kills e atribuo 0 para ele
	 */	
	private function startGameTotalKills() {
		if(!isset($this->out->{'game_' . $this->count}->total_kills))
			$this->out->{'game_' . $this->count}->total_kills = 0;		
	}

	/**
	 * inicializa a lista de jogador
	 */				
	private function initListPlayers() {
		if(!isset($this->out->{'game_' . $this->count}->players))
			$this->out->{'game_' . $this->count}->players = null;

		if(!is_array($this->out->{'game_' . $this->count}->players))
			$this->out->{'game_' . $this->count}->players = [];
	}

	private function getPlayName() {
		return Valid::playerName(Valid::playerInfo($this->line));
	}

	/**
	 * Atribuo a lista os usuarios do jogo atual
	 * por que eu coloquei o key com o nome do jogador, para poder facilitar que existise somente um na lista
	 * com isso mantenho a integridade da lista
	 */
	private function initListPlayersToArray() {
		if($this->getPlayName())
			$this->out->{'game_' . $this->count}->players[$this->getPlayName()] = $this->getPlayName();
	}

	/**
	 * Inicializo a lista de mortos
	 */
	private function initListKills() {
		if(!isset($this->out->{'game_' . $this->count}->kills))
			$this->out->{'game_' . $this->count}->kills = new \stdClass;		
	}

	/**
	 * Inicializo a lista de motivo da morte
	 */
	private function initListKillsByMeans() {
		if(!isset($this->out->{'game_' . $this->count}->kills_by_means))
			$this->out->{'game_' . $this->count}->kills_by_means = [];
	}

	/**
	 * inicializando a lista de mortos por jogador
	 */
	private function initListKillsPlayers() {
		if(!isset($this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}))
			$this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))} = new \stdClass;	
	}
	
	/**
	 * depois de inicializado a lista de mortos por jogador verifico se não existe o total e atribuo 0 para o total por usuario
	 */
	private function validTotalKillsPlayersExists() {
		if(!isset($this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->total))
			$this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->total = 0;
	}

	/**
	 * Aqui eu inicializo o usuario morto caso não exista na lista criando anterio, faço isso de precaução
	 */
	private function initListKillsPlayersNameKey() {
		if(!isset($this->out->{'game_' . $this->count}->kills->{Valid::died(Valid::kill($this->line))}))
			$this->out->{'game_' . $this->count}->kills->{Valid::died(Valid::kill($this->line))} = new \stdClass;
	}
	
	/**
	 *  verifico o total se não existe
	 */
	private function validKillsPlayersNameKeyTotalExists() {
		if(!isset($this->out->{'game_' . $this->count}->kills->{Valid::died(Valid::kill($this->line))}->total))
			$this->out->{'game_' . $this->count}->kills->{Valid::died(Valid::kill($this->line))}->total = 0;							
	}
	/**
	 * Aqui verifico se foi o <world> quem matou
	 */
	private function world() {
		if(Valid::killed(Valid::kill($this->line)) == "<world>") {

			$this->initListKillsPlayersNameKey();

			$this->validKillsPlayersNameKeyTotalExists();

			/**
			 * Subtraio o valor do jogador que foi morto pelo <world>
			 */
			$this->out->{'game_' . $this->count}->kills->{Valid::died(Valid::kill($this->line))}->total--;

		} else {
			/**
			 * aqui eu somo mais um para o jogador que for diferente di <world> e que matou alguem
			 * atenção se o jogador estava com 0 e é morto pelo <world> ele vai para -1 caso depois ele mate alguem sua pontuação será 
			 * somada mais 1 e voltarar para o 0, caso ele mate outro jogador novamente sua pontuação será somada e subirar para 1
			 */
			$this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->total++;
		}
		
	}

	private function dados() {
		/**
		 * Nessa parte eu inicializo os dados só mente para manter o controle e saber quem o jogador matou
		 */
		if(!isset($this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->dados))
			$this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->dados = null;

		if(!is_array($this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->dados)) 
			$this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->dados = [];
		
		/**
		 * Inicializando os dados 
		 * $s->text : esse atributo e mais para poder ver o texto que foi extraido da expressão regular
		 * 
		 * $s->killed : esse atributo é para poder saber o nome do jogador que matou alguem
		 * $s->died : esse atributo é para poder saber que o jogador matou 
		 * $s->causesDeath : e nesse atributo eu sei qual foi a causa da morte de cada um que foi morto por esse jogador
		 */
		$s = new \stdClass;
		$s->text = Valid::kill($this->line);
		$s->killed = Valid::killed(Valid::kill($this->line));
		$s->died = Valid::died(Valid::kill($this->line));
		$s->causesDeath = Valid::causesDeath(Valid::kill($this->line));

		/**
		 * atribuindo o valor da soma de todas as formas de morte no jogo
		 */
		$this->out->{'game_' . $this->count}->total_kills++;

		/**
		 * atribuindo a listas todas as causas da mortes que acontecer conforme o key gerado e assim vou somando
		 */
		if(!isset($this->out->{'game_' . $this->count}->kills_by_means[$s->causesDeath])) 
			$this->out->{'game_' . $this->count}->kills_by_means[$s->causesDeath] = 0;

		$this->out->{'game_' . $this->count}->kills_by_means[$s->causesDeath]++;

		/**
		 * atribuindo o $s a array
		 */
		$this->out->{'game_' . $this->count}->kills->{Valid::killed(Valid::kill($this->line))}->dados[] = $s;
		
	}

	/**
	 * verifico as ocorrencias de morte
	 */
	private function kill() {
		if(Valid::kill($this->line)) { 

			$this->initListKillsPlayers();
			
			$this->validTotalKillsPlayersExists();

			$this->world();

			$this->dados();
		}
		
	}

	public function init() {

		$this->start();

		foreach ($this->read() as $line) {

			$this->line = $line;

			$this->startGame();

			if($this->count <> 0) { 
				$this->startGameTotalKills();

				$this->initListPlayers();

				$this->initListPlayersToArray();

				$this->initListKills();

				$this->initListKillsByMeans();

				$this->kill();
			}
		}
		return $this->out;
	}

}