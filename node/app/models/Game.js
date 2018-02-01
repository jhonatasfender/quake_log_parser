var con = require('../../config/database.js');

con.query(`
	CREATE TABLE IF NOT EXISTS quake_log_parser.game (	
		id_game	 	INT(11) NOT NULL AUTO_INCREMENT,	
		name		VARCHAR(45) NOT NULL,	
		total_kills INT(11) NOT NULL,
		PRIMARY KEY (id_game)
	) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;	 
`, function(err, result) {		 
	if (err) throw err;	 
});

con.__table = "quake_log_parser.game";
con.__columns = ['name','total_kills']

function Game() {
	this.out = null;
	this.search = null;
}

Game.prototype.find = function($c,$v,$f) {
	return con.find($c,$v,$f);
}

Game.prototype.insert = function($f) {
	return con.insert($f);
}

Game.setOut = function($out) {
	this.out = $out;
}

Game.setSearch = function($search) {
	this.search = $search;
}

Game.get = function($f) {
	con.query(`
		SELECT 	p.name,
				IF(sum(k.total) IS NULL,0,sum(k.total)) AS total
		FROM quake_log_parser.game AS g
		LEFT JOIN quake_log_parser.players AS p ON p.id_game = g.id_game
		LEFT JOIN quake_log_parser.kills AS k ON k.id_players = p.id_players
		${Game.search && Game.search != "" ? "WHERE p.name LIKE '%" + Game.search + "%'" : ''}
		GROUP BY p.name
		ORDER BY total DESC;
	`, $f);

}

Game.save = function($f) {
	this.prototype.find('name',this.name,function(err, results) {
		if(results.length != 0) {

		} else {
			let a = Object.keys(Game.out).map(function(k) {
				return [ k , Game.out[k].total_kills ]
			})
			con.__result = a;
			con.insert($f);
		}
	});
}


module.exports = Game;