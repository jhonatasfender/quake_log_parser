var con = require('../../config/database.js');

con.query(`
    CREATE TABLE IF NOT EXISTS quake_log_parser.kills_by_means (
        id_kills_by_means   INT(11) NOT NULL AUTO_INCREMENT,
        name                VARCHAR(45) NOT NULL,
        id_game             INT(11) NOT NULL,
        total               INT(11) NOT NULL,

        PRIMARY KEY (id_kills_by_means),
            INDEX fk_kills_by_means_game_idx (id_game ASC),

        CONSTRAINT fk_kills_by_means_game
            FOREIGN KEY (id_game)
            REFERENCES quake_log_parser.game (id_game)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION
    ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
`, function(err, result) {       
    if (err) throw err;  
});


function KillsByMeans() {
    this.out = null;
}

KillsByMeans.prototype.find = function($c,$v,$f) {
    return con.find($c,$v,$f);
}

KillsByMeans.prototype.insert = function($f) {
    return con.insert($f);
}

KillsByMeans.setOut = function($out) {
    this.out = $out;
}

KillsByMeans.save = function($f) {
    con.__table = "quake_log_parser.kills_by_means";
    con.__columns = ['name','id_game','total']

    let a = new Array();
    Object.keys(KillsByMeans.out).map(function(k,v) {
        return Object.keys(KillsByMeans.out[k].kills_by_means).map(function(b,c) {
            a.push([b,v + 1,KillsByMeans.out[k].kills_by_means[b]])
        })
    })
    con.__result = a;
    con.insert($f);
}


module.exports = KillsByMeans;