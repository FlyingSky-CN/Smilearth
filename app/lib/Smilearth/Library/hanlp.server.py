#!/usr/bin/python3
# Smilearth > Library > hanlp.server.py

import json
import datetime
import tornado.ioloop
import tornado.web
import hanlp

print ('Loading...')

tokenizer = hanlp.load('PKU_NAME_MERGED_SIX_MONTHS_CONVSEG')

port = 7005
 
class MainHandler(tornado.web.RequestHandler):
    def get(self):
        q = self.get_argument('q')
        print (datetime.datetime.now(), q)
        a = json.dumps(tokenizer(q))
        self.write(a)
 
application = tornado.web.Application([(r"/hanlp", MainHandler), ])
 
if __name__ == "__main__":
    application.listen(port)
    print ('Listening port', port)
    tornado.ioloop.IOLoop.instance().start()