var async = require("async");

// 宣告Google API

// Imports the Google Cloud client library
const { Translate } = require("@google-cloud/translate");

// Your Google Cloud Platform project ID
const projectId = "udtest-1536892463367";

// Instantiates a client
const translate = new Translate({
  projectId: projectId
});

// 測試翻譯funciton
async function test_translate(array_word) {
  var final_word = [];
  var val = "";
  // Translates some text into Russian
  for (var i = 0; i < array_word.length; i++) {
    // The text to translate
    const text = array_word[i];
    // Google translate API
    await a_translate(text)
      .then(gg => {
        final_word.push(gg);
      })
      .catch(console.error);
  }
  return final_word;
}

async function a_translate(text) {
  const target = "zh-cn";

  var res = await translate.translate(text, target);
  var gg = res[0];
  return gg;
}

var json_analysis_word = JSON.parse(JSON.stringify("['測試','開會']"));
replace_analysis_word = json_analysis_word.replace(/'/g, '"');
array_analysis_word = JSON.parse(replace_analysis_word);

test_translate(array_analysis_word)
  .then(final_word => {
    console.log(final_word);
  })
  .catch(console.error);
