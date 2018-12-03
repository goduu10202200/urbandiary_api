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
  // Translates some text into Russian
  for (var i = 0; i < array_word.length; i++) {
    // The text to translate
    const text = array_word[i];
    // await a_translate(text)
    //   .then(gg => {
    //     final_word.push(gg);
    //   })
    //   .catch(console.error);
    const target = "zh-tw";
    var res = await translate.translate(array_word[i], "zh-tw");
    final_word.push(res[0]);
  }
  // array_word.forEach(
  //   element => {
  //     var res = translate.translate("余佩珊", "zh-tw");
  //     final_word.push(res[0]);
  //   },
  //   () => {
  //     return final_word;
  //   }
  // );
  return final_word;
}
async function a_translate(text) {
  const target = "zh-tw";
  var res = await translate.translate(text, target);
  var gg = res[0];
  return gg;
}

// 相近詞function
function similarword(word) {
  axios
    .post("http://localhost:8000/", {
      word: word
    })
    .then(function(response) {
      var json_word = JSON.parse(JSON.stringify(response.data));
      var json_analysis_word = JSON.parse(
        JSON.stringify(response.data["analysis_word"])
      );
      replace_analysis_word = json_analysis_word.replace(/'/g, '"');
      array_analysis_word = JSON.parse(replace_analysis_word);
      // 將Tag word push 到 array
      array_analysis_word.push(json_word["word"]);

      //Start translate words
      test_translate(array_analysis_word)
        .then(final_word => {
          // console.log(final_word);
          if (final_word != "Promise { <pending> }") {
            MongoClient.connect(
              url,
              function(err, db) {
                if (err) throw err;
                var dbo = db.db("ud");
                var tag_word = "";
                // 抓取Tag 文字
                if (final_word.length == 1) {
                  tag_word = final_word[0];
                  console.log("1111" + tag_word);
                } else {
                  tag_word = final_word[final_word.length - 1];
                  console.log("final" + tag_word);
                }
                // 設定新增資料
                var myobj = [
                  {
                    tag: tag_word,
                    similarword: final_word
                  }
                ];
                //新增資料;
                dbo.collection("test").insertMany(myobj, function(err, res) {
                  if (err) throw err;
                  console.log("插入的數量 : " + res.insertedCount);
                  db.close();
                });
              }
            );
          }
          return final_word;
        })
        .catch(console.error);
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
    // similarword(text);
    translate
      .translate(text, target)
      .then(results => {
        // 連接相近詞API
        similarword(results[0]);
      })
      .catch(err => {
        console.error("ERROR:", err);
      });
  }
}
