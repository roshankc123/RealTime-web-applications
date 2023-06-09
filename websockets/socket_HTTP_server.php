<?php
    /***
     *       
     *      used reference from https://gist.github.com/nox7/29d096bfda734a66215de7a0493f22b2, https://www.php.net/manual/en/sockets.examples.php
     *      run this code in a terminal using 'php {name}.php' or anyway you like
     *      it is a example of non-blocking IO thats continuosly read the socket and broadcast the message
     *      this is the example of simple web server using raw socket and same code is valid in c/c++with little tweek  
     * 
    ****/

    //for debugging set this to 1
    ini_set('error_reporting', 1);
    
    
    // Set time limit to indefinite execution(no timeout)
    
    set_time_limit (0);

    ob_implicit_flush();
    
    // Set the ip and port we will listen on
    
    $address = '127.0.0.1';
    
    $port = 8080;
    
    // Create a TCP Stream socket
    
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    
    // Bind the socket to an address/port
    
    socket_bind($sock, $address, $port) or die('Could not bind to address'.PHP_EOL);

    // printf('socket created'.PHP_EOL);
    
    
    
    // Start listening for connections
    
    socket_listen($sock, 5);
    
    
    
    // Non block socket type
    
    socket_set_nonblock($sock);
    
    

    $client = null;  // contain a list of associative array {'id', 'connection handler'}

    // Loop continuously
    while (true)
    
    {
    
        if ($newsock = @socket_accept($sock))
    
        {
            // printf('new sock');
            $client = $newsock;
            
            // $msg = 'HEAD / HTTP/1.1';
            
        }
        // HTTP/1.1 101 Switching Protocols
        // Upgrade: websocket
        // Connection: Upgrade
        // Sec-WebSocket-Accept: R4FJtNOv/fWfeBh48atEpCeUCak=
        // Sec-WebSocket-Extensions: permessage-deflate; server_max_window_bits=12; client_max_window_bits=12
        // Date: Fri, 09 Jun 2023 09:03:50 GMT
        // Server: Python/3.8 websockets/11.0.3
        
        
        if($client){
            if (@socket_recv($client, $string, 1024, MSG_DONTWAIT) === 0){

            }else{
                if($string){
                    // print_r($string.PHP_EOL);
                    $response = "HTTP/1.1 200 OK\r\n";
                    // $response .= "Upgrade: websocket\r\n";
                    $response .= "Connection: close\r\n";
                    // $response .= "Sec-WebSocket-Accept: $key\r\n";
                    $response .= "\r\n";
                    $response .= 'hello';
                    // $response .= '\r\n';
                    socket_write($client, $response, mb_strlen($response));

                }
                
            }
        }
        
        
    }
    socket_close($sock);

?>