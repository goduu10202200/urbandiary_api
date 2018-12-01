import synonyms
#from py_translator import Translator
from http.server import BaseHTTPRequestHandler, HTTPServer
import socketserver
import json
import cgi
from io import BytesIO
import simplejson




# Web Server 設定
class Handler(BaseHTTPRequestHandler): 
    def do_HEAD(self):
        self.send_response(200)
        self.send_header("Content-type", "text/html")
        self.end_headers()
    
    def do_POST(self):
        # Construct a server response.
        self.send_response(200)
        self.send_header(b'Content-type','text/html')
        self.data_string = self.rfile.read(int(self.headers['Content-Length']))
        self.end_headers()
        
        data = json.loads(self.data_string)

        
        # 設定Text Array
        array_text = data['word']
        array_trans_text = []
        array_analysis_text = []
        
        self.wfile.write(b"<html><head><title>Title goes here.</title></head><body><div class='dd'>")
        
        for index in range(len(array_text)):
            #trans_text = Translator().translate(text = array_text[index], dest='zh-cn').text
            #array_trans_text.append(trans_text)

            # 分析相近詞 => 存入到陣列
            array_analysis_text.append(synonyms.nearby(array_text[index]))

            # 設定變數
            byte1 = b'<p>'
            byte2 = b'</p>'
            word = json.dumps(str(array_text[index]) + ": " + str(array_analysis_text[index][0]) ).encode() 

            # 寫入到Web
            self.wfile.write(byte1)
            self.wfile.write(word)
            self.wfile.write(byte2)
            
        
        self.wfile.write(b"</div></body></html>")         
        return

print('Server listening on port 8000...')
# 執行Web Server
httpd = socketserver.TCPServer(('', 8000), Handler)
httpd.serve_forever()




