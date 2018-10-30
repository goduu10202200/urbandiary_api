var mysql = require("mysql");
// var connection = mysql.createConnection({
//   host: "localhost",
//   user: "root",
//   password: "1234",
//   database: "ud"
// });

var connection = mysql.createPool({
  host: "localhost",
  user: "root",
  password: "1234",
  database: "ud",
  waitForConnections: true,
  connectionLimit: 10
});

exports.connection = connection;
