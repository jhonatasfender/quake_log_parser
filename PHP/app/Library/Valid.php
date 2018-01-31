<?php 

namespace App\Library;

class Valid {
		
	public static function startGame($i) { 
		$l = preg_grep("/InitGame/", [$i]);
		return isset($l[0]) ? $l[0] : null;
	}

	/**
	 * captura a string toda das possiveis mortes
	 */
	public static function kill($i) { 
		preg_match("/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/", $i, $out);
		return isset($out[0]) ? $out[0] : null;
	}

	/**
	 * capturar o quem matou, com base no kill
	 */
	public static function killed($i) { 
		preg_match("/(?<=:\s)(.*?)(?=\skilled)/", $i, $out);
		return isset($out[0]) ? $out[0] : null;
	}

	/**
	 * capturar o quem morreu, com base no kill
	 */
	public static function died($i) { 
		preg_match("/(?<=killed\s)(.*?)(?=\sby)/", $i, $out);
		return isset($out[0]) ? $out[0] : null;
	}

	public static function causesDeath($i) { 
		preg_match("/(?<=by\s)(.*?)(?=$)/", $i, $out);
		return isset($out[0]) ? $out[0] : null;
	}

	public static function playerInfo($i) {
		$l = preg_grep("/ClientUserinfoChanged: \d n\\\\(.*?)\\\\/", [$i]);
		return isset($l[0]) ? $l[0] : null;
	}

	public static function playerName($i) { 
		preg_match("/(?<=\\\\)(.*?)(?=\\\\)/", $i, $out);
		return isset($out[0]) ? $out[0] : null;
	}

}