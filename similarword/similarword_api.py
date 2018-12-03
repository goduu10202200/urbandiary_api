import synonyms
from http.server import BaseHTTPRequestHandler, HTTPServer
import socketserver
import json

# Web Server 設定
class Handler(BaseHTTPRequestHandler): 
    def do_HEAD(self):
        self.send_response(200)
        self.send_header("Content-type", "text/html")
        self.end_headers()
    
    def do_POST(self):
        self.data_string = self.rfile.read(int(self.headers['Content-Length']))
        
        data = json.loads(self.data_string)
        
        # 設定Text Array
        array_text = data['word']
        array_analysis_text = []

        # 分析相近詞 => 存入到陣列
        array_analysis_text.append(synonyms.nearby(array_text))

        response = {
            'word':str(array_text),
            'analysis_word':str(array_analysis_text[0][0])
        }

        word = json.dumps(response).encode()
        # 寫入到Web
        self.do_HEAD()
        self.wfile.write(word)
        return
print('Server listening on port 8000...')
# 執行Web Server
httpd = socketserver.TCPServer(('', 8000), Handler)
httpd.serve_forever()




