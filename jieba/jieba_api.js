var mysql = require("mysql");
var http = require("http"); // 1 - 載入 Node.js 原生模組 http
var server = http.createServer(function(req, res) {
  // 2 - 建立server
  // 在此處理 客戶端向 http server 發送過來的 req。

  if (req.url == "/jieba") {
    res.writeHead(200, { "Content-Type": "text/html" });
    res.write("<html><body>This is Home Page.</body></html>");
    res.end();
    var pool = mysql.createPool({
      host: "localhost",
      user: "root",
      password: "1234",
      database: "ud",
      waitForConnections: true,
      connectionLimit: 10
    });

    var nodejieba = require("nodejieba");

    nodejieba.load({
      dict: nodejieba.DEFAULT_DICT,
      hmmDict: nodejieba.DEFAULT_HMM_DICT,
      userDict: "./public/jieba/dict.txt",
      idfDict: nodejieba.DEFAULT_IDF_DICT,
      stopWordDict: nodejieba.DEFAULT_STOP_WORD_DICT
    });

    var content, split_content;
    var topN = 4;
    //設定今天日期
    var date = new Date().getDate();
    var month = new Date().getMonth() + 1;
    var year = new Date().getFullYear();
    var utc = year + "-" + month + "-" + date;
    // var date = new Date();
    // date.setTime(date.getTime() + 8 * 60 * 60 * 1000);
    // var utc = date
    //   .toJSON()
    //   .slice(0, 10)
    //   .replace(/-/g, "-");

    pool.getConnection(function(err, connection) {
      if (err) {
        console.log(err);
      } else {
        var sql =
          "SELECT id,content FROM diary WHERE  date='" +
          utc +
          "'AND jieba_check!=1 ";
        connection.query(sql, function(err, result) {
          if (err) {
            console.log("[SELECT ERROR] - ", err.message);
          } else {
            var newJieba = [];
            var con_Jieba = "";
            var count_word = 0;
            for (var t = 0; t < result.length; t++) {
              content = result[t].content;
              split_content = content.split("\n");

              for (var i = 0; i < split_content.length; i++) {
                count_word = nodejieba.extract(split_content[i], topN).length;
                for (var j = 0; j < count_word; j++) {
                  newJieba[j] = nodejieba.extract(split_content[i], topN)[j][
                    "word"
                  ];
                  con_Jieba += newJieba[j] + ",";
                }
                //清除最後一個 ,
                con_Jieba = con_Jieba.substring(0, con_Jieba.length - 1);
                sql =
                  "INSERT INTO jieba (word,sentence,diary_id) VALUES ('" +
                  con_Jieba +
                  "', '" +
                  split_content[i] +
                  "', '" +
                  result[t].id +
                  "')";
                connection.query(sql);
                con_Jieba = "";
              }
              sql =
                "UPDATE diary SET  jieba_check = 1 " +
                "WHERE id = " +
                result[t].id +
                "";
              connection.query(sql);
            }

            connection.release();
          }
        });
      }
    });
  }
});

server.listen(5000); //3 - 進入此網站的監聽 port, 就是 localhost:xxxx 的 xxxx

console.log("Node.js web server at port 5000 is running..");
