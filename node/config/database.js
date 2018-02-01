var mysql = require('mysql');
var con = mysql.createConnection({
	host: "localhost",
	user: "root",
	password: "P40s3g2015"
});

con.__ins = null;
con.__sql = null;
con.__table = null;
con.__result = [];
con.__fields = null;
con.__columns = [];

con.find = function($c,$v,$f) {
	con.__ins = "";
	if(Array.isArray($c)) {
		for(let $field in $c) 
			con.__ins += $field + " = ? AND ";
		
		con.__ins = con.__ins.subtring(0,con.__ins.length - 5);
		con.__sql = `SELECT * FROM ${con.__table} WHERE ${con.__ins}`;

		con.__result = $c.map(a => a);
	} else {
		con.__sql = `SELECT * FROM ${con.__table} WHERE ${$c} = ?`;

		con.__result.push($v);
	}
	return con.query(con.__sql, con.__result, $f);
}

con.insert = function($f) {
	con.__ins = new Array();
	if(con.__result.length != 0) {
		for(let $field in con.__columns) 
			con.__ins.push(con.__columns[$field]);
		con.__ins = con.__ins.join(',');
		let a = con.__result;
		con.__result = new Array();
		con.__result.push(a);
		con.__sql = `INSERT INTO ${con.__table} (${con.__ins}) VALUES ? `;

	} else { 

		for(let $field in con.__columns) 
			con.__ins.push("?");
		con.__fields = Object.keys(con.__columns).join(',');
		con.__result = Object.keys(con.__columns).map(function (k) { 
			return con.__columns[k]; 
		});
		con.__sql = `INSERT INTO ${con.__table} (${con.__fields}) VALUES (${con.__ins})`;
	}
	return con.query(con.__sql, con.__result, $f);
}

module.exports = con;