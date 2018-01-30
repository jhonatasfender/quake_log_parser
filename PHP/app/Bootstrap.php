<?php 
namespace App;

class Bootstrap {

	public $game;

	private $out;
	private $count = 0;
	private $k;
	
	public function __construct() {
		$this->out = new \stdClass;
		
		$this->game = new \App\Models\Game();

		foreach (file(__DIR__ . "/log/games.log") as $line) {
			if(is_string($this->startGame($line))) {
				$this->count++;
				$this->out->{'game_' . $this->count} = new \stdClass; 
			}
			if($this->count <> 0) { 
				$this->out->{'game_' . $this->count}->total_kills = 0;
				
				if(!is_array($this->out->{'game_' . $this->count}->players))
					$this->out->{'game_' . $this->count}->players = [];

				if($this->playerName($this->playerInfo($line)))
					$this->out->{'game_' . $this->count}->players[$this->playerName($this->playerInfo($line))] = $this->playerName($this->playerInfo($line));

				if(!isset($this->out->{'game_' . $this->count}->kills))
					$this->out->{'game_' . $this->count}->kills = new \stdClass;

				if(trim($this->playerName($this->playerInfo($line))) <> "") {
					if(!isset($this->out->{'game_' . $this->count}->kills->{$this->playerName($this->playerInfo($line))}))
						$this->out->{'game_' . $this->count}->kills->{$this->playerName($this->playerInfo($line))} = 0;

					$this->out->{'game_' . $this->count}->kills->{$this->playerName($this->playerInfo($line))} = 0;
				}

				// d($this->kill($line));
			}
		}

		d($this->out);

	}

	private function startGame($i) { 
		return preg_grep("/InitGame/", [$i])[0];
	}

	private function endGame($i) { 
		return preg_grep("/--------+/", [$i])[0];
	}

	private function kill($i) { 
		return preg_grep("/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/", [$i])[0];
	}

	private function killed($i) { 
		return preg_grep("/(?<=:\s)(.*?)(?=\skilled)/", [$i])[0];
	}

	private function died($i) { 
		return preg_grep("/(?<=killed\s)(.*?)(?=\sby)/", [$i])[0];
	}

	private function causesDeath($i) { 
		return preg_grep("/(?<=by\s)(.*?)(?=$)/", [$i])[0];
	}

	private function playerInfo($i) {
		return preg_grep("/ClientUserinfoChanged: \d n\\\\(.*?)\\\\/", [$i])[0];
	}

	private function playerName($i) { 
		preg_match("/(?<=\\\\)(.*?)(?=\\\\)/", $i, $out);
		return $out[0];
	}
}