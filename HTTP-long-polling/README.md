# HTTP long polling

HTTP long polling is a method for real time web communication over TCP. it uses a client-server model. the idea is , a client sends a regular http request to a server and server delays the response until it either have a new data to send or request timeout occurs.

here i have provided a simple example of chat application using HTTP long polling. it uses a php built-in server in a non-blocking mode.
