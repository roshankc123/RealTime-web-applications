# HTTP long polling

HTTP long polling is a method for real time web communication over TCP. it uses a client-server model. the idea is , a client sends a regular http request to a server and server delays the response until it either have a new data to send or request timeout occurs.

here i have provided a simple example of chat application using HTTP long polling. it uses a php built-in server in a non-blocking mode.

reference: <https://www.pubnub.com/blog/http-long-polling/>

implementation of example:

1. PHP_CLI_SERVER_WORKERS=4 php -S 127.0.0.1:8080 -t .
   1. assume 4 client concurrently
   2. can be any number desired
2. file a and b are used to store message from id a and b
   1. I have assumed two id a and b
   2. if id is changed, change the file name to respective id
   3. or database can be used
3. go to the url
4. set id of one to a and another b
5. enjoy messaging
