var http = require("http");
let axios = require("axios");
cheerio = require("cheerio");

var server = http.createServer(function(req, res) {
  if (req.url == "/similarword") {
    res.writeHead(200, { "Content-Type": "text/html" });
    res.write("<html><body>");
    res.write("</body></html>");
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
      similarword(JSON.parse(jsonparse_jsonstring["word"]));
      //   console.log(jsonparse_jsonstring);
    });
  }
});

server.listen(6000); //3 - 進入此網站的監聽 port, 就是 localhost:xxxx 的 xxxx

console.log("Node.js web server at port 6000 is running..");

// 相近詞API
function similarword(word) {
  axios
    .post("http://localhost:8000/", {
      word: word
    })
    .then(function(response) {
      // 取得網頁所有資料
      let $ = cheerio.load(response.data);
      // 取得 class dd 之後的資料
      const table_tr = $(".dd");
      // 取得P標籤
      const test = table_tr.eq(0).find("p");
      // 顯示所有P標籤的資料
      for (var i = 0; i < test.length; i++) {
        console.log(JSON.parse(test.eq(i).text()));
      }
    })
    .catch(function(e) {
      console.log(e);
    });
}
