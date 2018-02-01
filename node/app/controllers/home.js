var fs = require('fs');
var path = require('path');
var valid = require('../../lib/valid.js');

var game = require('../models/Game.js');
var KillsByMeans = require('../models/KillsByMeans.js');
var Players = require('../models/Players.js');
var Kills = require('../models/Kills.js');


exports.home = function(req, res) {

    fs.readFile(path.join(__dirname, '../log/games.log'), 'utf8', function(err, data) {
        if (err) throw err;

        let out = {},
            count = 0,
            s = data.split(/\n/g);

        for (let i in s) {

            if (valid.startGame(s[i])) {
                count++;
                out['game_' + count] = {
					total_kills: 0,
					players: [],
					kills: {},
					kills_by_means: []
                };
            }

            if(out['game_' + count] != undefined) {
            	if(valid.playerName(s[i])) {
            		if(!out['game_' + count].players.includes(valid.playerName(s[i]))) { 
            			out['game_' + count].players.push(valid.playerName(s[i]));
		            	out['game_' + count].kills[valid.playerName(s[i])] = {
		            		dados: [],
		            		total: 0
		            	} 
		            }

            	}
	            // out['game_' + count].kills[valid.playerName(s[i])]
	            if(valid.playerName(s[i]) == '<world>') {

            		if(out['game_' + count].kills[valid.died(s[i])] == undefined) { 
		            	out['game_' + count].kills[valid.died(s[i])] = {
		            		dados: [],
		            		total: 0
		            	} 
		            }
		            	
	            	out['game_' + count].kills[valid.died(s[i])].total--;
	            	out['game_' + count].kills[valid.killed(s[i])].total++;
	            } else if(valid.killed(s[i])) {
	            	out['game_' + count].kills[valid.killed(s[i])].total++;
	            }
	            if(out['game_' + count].kills[valid.playerName(s[i])] != undefined) { 

					out['game_' + count].total_kills++;

					if(valid.causesDeath(valid.kill(s[i])) != null) { 
		            	if(out['game_' + count].kills_by_means[valid.causesDeath(valid.kill(s[i]))] == null)
		            		out['game_' + count].kills_by_means[valid.causesDeath(valid.kill(s[i]))] = 0;

		            	if(out['game_' + count].kills_by_means[valid.causesDeath(valid.kill(s[i]))] != null)
		            		out['game_' + count].kills_by_means[valid.causesDeath(valid.kill(s[i]))]++;
		            }


					out['game_' + count].kills[valid.playerName(s[i])].dados.push({
						text: valid.kill(s[i]),
						killed: valid.killed(valid.kill(s[i])),
						died: valid.died(valid.kill(s[i])),
						causesDeath: valid.causesDeath(valid.kill(s[i]))
					});
				}
            }
        }
        game.setOut(out);
		game.save(function(err, results) {
			if (err) throw err;	 
			
			KillsByMeans.setOut(out);
			KillsByMeans.save(function(err, results) {
				if (err) throw err;	 

			});

			Players.setOut(out);
			Players.save(function(err, results) {
				if (err) throw err;	 

				Kills.setOut(out);
				Kills.save(function(err, results) {
					if (err) throw err;	 
					res.redirect("/list");
				});
			});

			
		});
    });

}

exports.list = function(req, res) {
	game.get(function(err, results) {
		if (err) throw err;
	    res.render('index.ejs', {
	        out: results,
	    });
	})
}

exports.search = function(req, res) {
	try {
		if(req.body.search)
			res.redirect("/list");


		game.setSearch(req.body.search);
		game.get(function(err, results) {
			if (err) throw err;
		    res.render('index.ejs', {
		        out: results,
		    });
		})
	} catch(e) {
	
	}

}