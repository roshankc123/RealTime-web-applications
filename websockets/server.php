<?php
    /***
     *       
     *      used reference from https://gist.github.com/nox7/29d096bfda734a66215de7a0493f22b2, https://www.php.net/manual/en/sockets.examples.php
     *      run this code in a terminal using php raw_socket.php or anyway you like
     *      it is a example of non-blocking IO thats continuosly read the socket and broadcast the message
     *      this is the example of multi chat application using raw socket and same code is valid in c/c++with little tweek  
     * 
    ****/

    //for debugging set this to 1
    include 'websocket_frames.php';

    $frame = new Frames();
    ini_set('error_reporting', 0);
    
    
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
        
        
        if($client){
            if (!($string = socket_read($client, 4096))){

            }else{
                if($string){
                    print_r($string.PHP_EOL);
                    preg_match('/Sec-WebSocket-Key: (.*)\r\n/', $string, $matches);  //reges by chatGPT
                    if(count($matches)){
                        $key = $matches[1];
                        $key1 = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
                        // echo $key;
                        $response = "HTTP/1.1 101 Switching Protocols\r\n";
                        $response .= "Upgrade: websocket\r\n";
                        $response .= "Connection: upgrade\r\n";
                        $response .= "Sec-WebSocket-Accept: $key1\r\n";
                        $response .= "\r\n";
                        socket_write($client, $response, mb_strlen($response));
                        print_r(' header sent'.PHP_EOL);
                    }
                    else{
                        var_dump($frame->decode($string));
                    }

                }
                
                
                
                // socket_close($sock);
                // exit();
            }
        }
        
        
    }
    socket_close($sock);

    function genWebSockKey($string){
        $lines = explode(PHP_EOL,$string);
        // var_dump($lines);
                    $headers = [];

                    foreach ($lines as $index=>$header){
                        if ($index > 0){

                            // Trim the header of whitespace - is it blank?
                            if (trim($header) !== ""){

                                // Extract the header name and value
                                preg_match("/([^\:]+): \s*(.+)/", $header, $matches);
                                $headers[$matches[1]] = $matches[2];
                            }
                        }
                    }

                    $uuid = '3a703da1-93db-41dd-b306-36e40d824288';
                    // print_r($headers['Sec-WebSocket-Key']);
                    // var_dump($headers);
                    return base64_encode(sha1($headers['Sec-WebSocket-Key'].$uuid, true));

    }
?>