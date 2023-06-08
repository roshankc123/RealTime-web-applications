import flask
# import requests
# from OpenSSL import SSL
from flask_cors import CORS
import asyncio
from websockets.server import serve
import multiprocessing

app = flask.Flask(__name__)
app.secret_key = ''   #for debug toolbar(hex key)
CORS(app) #recommended to use specific origins

wsocks = []

async def echoa(websocket, message):
    await websocket.send(message)

async def echo(websocket):
    wsocks.append(websocket)
    print(wsocks)
    async for message in websocket:
        print("second player sent", message)
        for conn in wsocks:
            await echoa(conn, message)

async def websockMain():
    async with serve(echo, "localhost", 8765):
        await asyncio.Future()  # run forever

def wsconn():
    asyncio.run(websockMain())


@app.route('/', methods=['GET'])
def index():
    return flask.render_template('index.html')

@app.route('/ws', methods=['GET'])
def ws():
    return "ws://localhost:8765"

if __name__ == '__main__':
    ws_bound = 0
    thr = multiprocessing.Process(target=wsconn)
    thr.start()
    app.run(port=8000)