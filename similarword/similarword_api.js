var http = require("http");
var async = require("async");
var axios = require("axios");
var MongoClient = require("mongodb").MongoClient;
var url = "mongodb://localhost:27017/";
//cheerio = require("cheerio");

// Imports the Google Cloud client library
const { Translate } = require("@google-cloud/translate");

// Your Google Cloud Platform project ID
const projectId = "udtest-1536892463367";

// Instantiates a client
const translate = new Translate({
  projectId: projectId
});

var server = http.createServer(function(req, res) {
  if (req.url == "/similarword") {
    res.writeHead(200, { "Content-Type": "text/html" });
    // res.write("<html><body>");
    // res.write("</body></html>");
    res.end();
  }
  if (req.method == "POST") {
    var jsonString = "";
    var jsonparse_jsonstring = "";
    req.on("data", function(data) {
      jsonString += data;
    });

    req.on("end", function() {
      jsonparse_jsonstring = JSON.parse(jsonString);
      // similarword(JSON.parse(jsonparse_jsonstring["word"]));
      translateword(JSON.parse(jsonparse_jsonstring["word"]));
      //console.log(jsonparse_jsonstring);
    });
  }
});

server.listen(6000); //3 - 進入此網站的監聽 port, 就是 localhost:xxxx 的 xxxx

console.log("Node.js web server at port 6000 is running..");

// 測試翻譯funciton
async function test_translate(array_word) {
  var final_word = [];
  // The target language
  const target = "zh-cn";
  // Translates some text into Russian
  for (var i = 0; i < array_word.length; i++) {
    // The text to translate
    const text = array_word[i];
    // Push array
    var val = await a_translate(text);
    final_word.push(val);
  }
  return final_word;
}

async function a_translate(text) {
  var k = "";
  translate
    .translate(text, target)
    .then(results => {
      k = results[0];
    })
    .catch(err => {
      console.error("ERROR:", err);
    });
  return k;
}

// 相近詞function
function similarword(word) {
  //translateword(word);
  var array_t = [];

  axios
    .post("http://localhost:8000/", {
      word: word
    })
    .then(function(response) {
      // // 取得網頁所有資料
      // let $ = cheerio.load(response.data);
      // // 取得 class dd 之後的資料
      // const table_tr = $(".dd");
      // // 取得P標籤
      // const test = table_tr.eq(0).find("p");
      // // 顯示所有P標籤的資料
      // for (var i = 0; i < test.length; i++) {
      //   console.log(JSON.parse(test.eq(i).text()));
      //   //return JSON.parse(test.eq(i).text());
      // }
      var json_word = JSON.parse(JSON.stringify(response.data));
      var json_analysis_word = JSON.parse(
        JSON.stringify(response.data["analysis_word"])
      );
      replace_analysis_word = json_analysis_word.replace(/'/g, '"');
      array_analysis_word = JSON.parse(replace_analysis_word);

      // console.log("word" + json_word["word"]);
      // for (var i = 0; i < array_analysis_word.length; i++) {
      //   console.log(array_analysis_word[i]);
      // }

      console.log(test_translate(array_analysis_word));

      // MongoClient.connect(
      //   url,
      //   function(err, db) {
      //     if (err) throw err;
      //     var dbo = db.db("ud");

      //     // 設定新增資料
      //     var myobj = [
      //       {
      //         tag: split_json_word[0],
      //         similarword: split_json_word[1]
      //       }
      //     ];

      //     //新增資料;
      //     dbo.collection("test").insertMany(myobj, function(err, res) {
      //       if (err) throw err;
      //       console.log("插入的數量 : " + res.insertedCount);
      //       db.close();
      //     });
      //   }
      // );
    })
    .catch(function(e) {
      console.log(e);
    });
}

// 翻譯function
function translateword(tr_word) {
  // The target language
  const target = "zh-cn";
  // Translates some text into Russian
  for (var i = 0; i < tr_word.length; i++) {
    // The text to translate
    const text = tr_word[i];
    similarword(text);
    // translate
    //   .translate(text, target)
    //   .then(results => {
    //     // const translation = results[0];
    //     // console.log("Text:" + text);
    //     // console.log(`Translation: ${translation}`);
    //     similarword(results[0]);
    //   })
    //   .catch(err => {
    //     console.error("ERROR:", err);
    //   });
  }
}
