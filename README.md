# ab

##

### what is non-blocking IO?

in non-blocking IO, multiple concurrent request can be served. this type of server don't wait for ongoing request to complete or the send or receive function dont block the operation of other IO.
contrary to blocking IO, where one request or send/receive function completes before another.

## about

this repository include a php and python based examples of different approaches and protocols of real-time web applications.
it includes:

1. HTTP regular polling
2. HTTP long pooling
3. Server sent events
4. Websockets

## funfact

HTTP regular polling is is frequently used. server sent events is not supported by all. HTTP long polling and Websockets are used frequently. they are used alternatively. whenever, websocket are supported it is preferred, and if not HTTP long polling is used.
