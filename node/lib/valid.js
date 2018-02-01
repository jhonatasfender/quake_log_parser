
exports.startGame = function($i) { 
	return /InitGame/.exec($i);
}

/**
 * captura a string toda das possiveis mortes
 */
exports.kill = function($i) { 
	return /:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/.exec($i);
}

/**
 * capturar o quem matou, com base no kill
 */
exports.killed = function($i) { 
	let l = /(\d:\s)(.*)(?=\skilled)/.exec($i);
	return l ? l[2].replace(/\d:\s/,'') : null;
}

/**
 * capturar o quem morreu, com base no kill
 */
exports.died = function($i) { 
	let a = /(killed\s)(.*?)(?=\sby)/.exec($i);

	return a != null ? a[0].replace(/killed\s/,'') : null;
}

exports.causesDeath = function($i) { 
	let a = /by\s[A-Z_]+/.exec($i);
	return a != null ? a[0].replace(/by\s/,'') : null;
}

exports.playerInfo = function($i) {
	let a = /ClientUserinfoChanged: \d n\\(.*?)\\/.exec($i);

	return a != null ? a[0].replace(/ClientUserinfoChanged:\s\d\sn\\/,'').replace(/\\/,'') : null;
}

exports.playerName = function($i) { 
	let a = /(ClientUserinfoChanged:\s\d\sn\\|<)(.*?)(\\|>)/.exec($i);
	return a != null ? a[0].replace(/ClientUserinfoChanged:\s\d\sn\\|\\|\\\\/,'').replace(/\\/,'') : null;
}
