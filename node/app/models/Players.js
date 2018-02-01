var con = require('../../config/database.js');

con.query(`
    CREATE TABLE IF NOT EXISTS quake_log_parser.players (
        id_players  INT(11) NOT NULL AUTO_INCREMENT,
        name        VARCHAR(45) NOT NULL,
        id_game     INT(11) NOT NULL,

        PRIMARY KEY (id_players),
            INDEX fk_players_game_idx (id_game ASC),
        
        CONSTRAINT fk_players_game
            FOREIGN KEY (id_game)
            REFERENCES quake_log_parser.game (id_game)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION
    ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
`, function(err, result) {       
    if (err) throw err;  
});


function Players() {
    this.out = null;
}

Players.prototype.find = function($c,$v,$f) {
    return con.find($c,$v,$f);
}

Players.prototype.insert = function($f) {
    return con.insert($f);
}

Players.setOut = function($out) {
    this.out = $out;
}

Players.save = function($f) {
    con.__table = "quake_log_parser.players";
    con.__columns = ['name','id_game']

    let a = new Array();
    Object.keys(Players.out).map(function(k,v) {
        return Object.keys(Players.out[k].players).map(function(b,c) {
            a.push([Players.out[k].players[b],v + 1])
        })
    })
    con.__result = a;
    con.insert($f);
}


module.exports = Players;