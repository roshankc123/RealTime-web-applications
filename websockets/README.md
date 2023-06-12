# real-time communication using websockets

here is a example of both raw websocket server using php (or c/c++) and python. python uses a library of websocket whereas php is written from scratch. in the process i learned how to create a webserver using sockets, and about websockets.

## websocket

websocket is an protocol, just like http, for bi-directional real-time web through web. it has its own header format and handshaking protocol. this protocol is recognized by most of modern browsers. upon receving a correct header from server, browser takes care of all required handshakes.

A dedicated port is needed for websocket server, which actively engage with user connection in non-blocking mode. 
for more, please refer to <https://datatracker.ietf.org/doc/html/rfc6455>