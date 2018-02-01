var con = require('../../config/database.js');

con.query(`
    CREATE TABLE IF NOT EXISTS quake_log_parser.kills (
        id_kills    INT(11) NOT NULL AUTO_INCREMENT,
        name        VARCHAR(45) NOT NULL,
        id_players  INT(11) NOT NULL,
        total       VARCHAR(45) NOT NULL,

        PRIMARY KEY (id_kills),
            INDEX fk_kills_players_idx (id_players ASC),
            
        CONSTRAINT fk_kills_players
            FOREIGN KEY (id_players)
            REFERENCES quake_log_parser.players (id_players)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION
    ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
`, function(err, result) {       
    if (err) throw err;  
});


function Kills() {
    this.out = null;
}

Kills.prototype.find = function($c,$v,$f) {
    return con.find($c,$v,$f);
}

Kills.prototype.insert = function($f) {
    return con.insert($f);
}

Kills.setOut = function($out) {
    this.out = $out;
}

Kills.save = function($f) {
    con.__table = "quake_log_parser.kills";
    con.__columns = ['name','id_players','total']

    let a = new Array();
    Object.keys(Kills.out).map(function(k,v) {
        return Object.keys(Kills.out[k].players).map(function(b,c) {
            return Object.keys(Kills.out[k].kills).map(function(d,e) {
                a.push([d,parseInt(b)+1,Kills.out[k].kills[d].total])
            })

        })
    })
    con.__result = a;
    con.insert($f);
}


module.exports = Kills;
