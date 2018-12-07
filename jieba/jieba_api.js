// 載入 Node.js 原生模組 http
var http = require("http");
// 載入 axios
let axios = require("axios");

//  建立server
// 在此處理 客戶端向 http server 發送過來的 req。
var server = http.createServer(function(req, res) {
  if (req.url == "/jieba") {
    res.writeHead(200, { "Content-Type": "text/html" });
    res.write("<html><body>This is Home Page.</body></html>");
    res.end();
    // Jieba 設定
    var nodejieba = require("nodejieba");
    nodejieba.load({
      dict: nodejieba.DEFAULT_DICT,
      hmmDict: nodejieba.DEFAULT_HMM_DICT,
      userDict: "./public/jieba/dict.txt",
      idfDict: nodejieba.DEFAULT_IDF_DICT,
      stopWordDict: nodejieba.DEFAULT_STOP_WORD_DICT
    });

    // 設定變數

    //設定今天日期
    var date = new Date().getDate();
    var dd = (date > 9 ? "" : "0") + date;
    var month = new Date().getMonth() + 1;
    var year = new Date().getFullYear();
    var utc = year + "-" + month + "-" + dd;

    var topN = 4;
    var content, split_content;

    var newJieba = [];
    var con_Jieba = "";
    var array_Jieba = [];
    var count_word = 0;
    var split_content = "";
    var ObjectID = require("mongodb").ObjectID;
    var MongoClient = require("mongodb").MongoClient;
    var url = "mongodb://localhost:27017/";

    MongoClient.connect(
      url,
      function(err, db) {
        if (err) throw err;
        var dbo = db.db("ud");
        var whereStr = {
          date: utc,
          jieba_check: "0"
        }; // 查询条件
        dbo
          .collection("diary")
          .find(whereStr)
          .toArray(function(err, result) {
            // 返回集合中所有数据
            if (err) throw err;

            for (var t = 0; t < result.length; t++) {
              content = result[t].content;

              // 用換行分行
              split_content = content.split("\n");
              // console.log(split_content);
              // 讀取每一行資料
              for (var i = 0; i < split_content.length; i++) {
                // 判斷每行是否為空值
                if (split_content[i] != "") {
                  // 斷詞每一行資料
                  count_word = nodejieba.extract(split_content[i], topN).length;

                  // 將斷詞結果用 ' ， ' 連接
                  for (var j = 0; j < count_word; j++) {
                    newJieba[j] = nodejieba.extract(split_content[i], topN)[j][
                      "word"
                    ];
                    array_Jieba.push(newJieba[j]);
                    con_Jieba += newJieba[j] + ",";
                  }

                  //清除最後一個 ' , '
                  con_Jieba = con_Jieba.substring(0, con_Jieba.length - 1);

                  // 設定新增資料
                  var myobj = [
                    {
                      word: con_Jieba,
                      sentence: split_content[i],
                      diary_id: result[t]["_id"]
                    }
                  ];

                  //新增資料;
                  dbo.collection("jieba").insertMany(myobj, function(err, res) {
                    if (err) throw err;
                    //console.log("插入的數量 : " + res.insertedCount);
                    db.close();
                  });
                  con_Jieba = "";
                  //}
                }
                // 這邊要有更新資料
                whereStr2 = { _id: ObjectID(result[t]._id) }; // 查询条件
                updateStr = { $set: { jieba_check: "1" } };
                dbo
                  .collection("diary")
                  .updateOne(whereStr2, updateStr, function(err, res) {
                    if (err) throw err;
                    console.log("Update data successfuly");
                    db.close();
                  });
              }
            }
            // 傳送關鍵字API
            //sendword(array_Jieba);
            db.close();
          });
      }
    );
  }
});

server.listen(5000); //3 - 進入此網站的監聽 port, 就是 localhost:xxxx 的 xxxx

console.log("Node.js web server at port 5000 is running..");

// 傳送關鍵字
function sendword(word) {
  axios
    .post("http://localhost:6000/similarword", {
      word: JSON.stringify(word)
    })
    .then(function(response) {
      console.log(response.data);
    })
    .catch(function(e) {
      console.log(e);
    });
}
