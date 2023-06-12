import flask
# import requests
# from OpenSSL import SSL
from flask_cors import CORS
import asyncio
from websockets.server import serve
import multiprocessing
import json

app = flask.Flask(__name__)
app.secret_key = ''   #for debug toolbar(hex key)
CORS(app) #recommended to use specific origins

wsocks = []

async def echoa(websocket, message):
    await websocket.send(message)

async def echo(websocket):
    id = len(wsocks)
    wsock = {
        'id' : id,
        'conn' : websocket
    }
    wsocks.append(wsock)
    # print(wsocks)
    async for message in websocket:
        # print("second player sent", message)
        messageTmp = {
            'sender' : id,
            'message' : message
        }
        for wsock in wsocks:
            await echoa(wsock['conn'], json.dumps(messageTmp))

async def websockMain():
    async with serve(echo, str.split(flask.request.host, ':')[0], 8765):
        await asyncio.Future()  # run forever

def wsconn():
    asyncio.run(websockMain())


@app.route('/', methods=['GET'])
def index():
    return flask.render_template('index.html', websocket_url = ws())

@app.route('/ws', methods=['GET'])
def ws():
    # print(flask.request.host)
    return "ws://" + str.split(flask.request.host, ':')[0] + ":8765"

if __name__ == '__main__':
    # start background process
    ws_bound = 0
    thr = multiprocessing.Process(target=wsconn)
    thr.start()
    # #####
    app.run(port=8000)