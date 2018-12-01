let axios = require("axios");
cheerio = require("cheerio");

sendword();

// 傳送關鍵字
function sendword() {
  axios
    .post("http://localhost:6000/similarword", {
      word:'["跑步","单字","上班","中国台湾","民进党"]',
    })
    .then(function(response) {
      console.log(response.data);
    })
    .catch(function(e) {
      console.log(e);
    });
}
