import synonyms
from py_translator import Translator

# 設定Text Array
array_text = ["跑步","單字","上班","中國台灣","民進黨"]
array_trans_text = []
array_analysis_text = []


for index in range(len(array_text)):
    trans_text = Translator().translate(text = array_text[index], dest='zh-cn').text
    array_trans_text.append(trans_text)
    array_analysis_text.append(synonyms.nearby(array_trans_text[index]))
    print(array_trans_text[index],": ",array_analysis_text[index][0])

    
    
    #print(str_sport,": %s%s" % (synonyms.nearby(trans_sport)))
#print(array_trans_text[2])
#translator = Translator()
# <Translated src=ko dest=en text=Good evening. pronunciation=Good evening.>
#translator.translate('안녕하세요.', dest='ja')
# <Translated src=ko dest=ja text=こんにちは。 pronunciation=Kon'nichiwa.>
#>>> translator.translate('veritas lux mea', src='la')
# <Translated src=la dest=en text=The truth is my light pronunciation=The truth is my light>






#print("瘦身: %s%s" % (synonyms.nearby("瘦身"))) # 获取近义词
#print("運動: %s%s" % (synonyms.nearby("运动")))
#print("开会: %s%s" % (synonyms.nearby("开会")))
#print("脚踏车: %s%s" % (synonyms.nearby("骑脚踏车")))
#print("上课: %s%s" % (synonyms.nearby("上课")))
#print("拉面: %s%s" % (synonyms.nearby("拉面")))
#print("垃圾: %s%s" % (synonyms.nearby("垃圾")))
#print("拉机: %s%s" % (synonyms.nearby("拉机")))
#print("NOT_EXIST: %s%s" % (synonyms.nearby("NOT_EXIST")))
