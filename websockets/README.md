# real-time communication using websockets

here is a example of both raw websocket server using php (or c/c++) and python. python uses a library of websocket whereas php is written from scratch. in the process i learned how to create a webserver using sockets, and about websockets.

## websocket

websocket is an protocol, just like http, for bi-directional real-time web through web. it has its own header format and handshaking protocol. this protocol is recognized by most of modern browsers. upon receving a correct header from server, browser takes care of all required handshakes.

A dedicated port is needed for websocket server, which actively engage with user connection in non-blocking mode.
response header should contain following fields:

1. HTTP/1.1 101 Switching Protocols
2. Upgrade: websocket
3. Connection: upgrade
4. Sec-WebSocket-Accept: {key}

to generate a {key}, refer to following steps:

1. get the value of 'Sec-WebSocket-Key:' from request header -> req_key
2. concate(req_key, unique_constant_UUID) -> cat_key
3. sha1(cat_key) -> hash_key
4. bas64_encode(hash_key) -> key

for more information, please refer to <https://datatracker.ietf.org/doc/html/rfc6455>

## how to run the example code

for flask:

1. pip install -r requirements.txt
2. python app_basic.py

for php:

1. in one tab, run: php -S 127.0.0.1:8080 -t .
2. in another tab, run: php server.php

once a server is running, open the website with designated address, and enjoy.
