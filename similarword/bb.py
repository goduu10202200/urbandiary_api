import http.server
import socketserver

class Handler(http.server.SimpleHTTPRequestHandler):

    def do_HEAD(self):
        self.send_response(200)
        self.send_header("Content-type", "text/html")
        self.end_headers()
        
    def do_GET(self):
        # Construct a server response.
        self.send_response(200)
        self.send_header(b'Content-type','text/html')
        self.end_headers()

        
        self.wfile.write(b"<html><head><title>Title goes here.</title></head>")
        self.wfile.write(b"<body><div class='dd'><p>This is a test.</p></div>")
        self.wfile.write(b"</body></html>")
        return


print('Server listening on port 8000...')
httpd = socketserver.TCPServer(('', 8000), Handler)
httpd.serve_forever()
