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
    
    socket_listen($sock);
    
    
    
    // Non block socket type
    
    socket_set_nonblock($sock);
    
    

    $client = [];  // contain a list of associative array {'id', 'connection handler'}
    $newclient = null;
    // Loop continuously
    while (true)
    
    {
    
        if ($newsock = @socket_accept($sock))
    
        {
            printf('new user'.PHP_EOL);
            $conn['id'] = count($client);
            $conn['conn'] = $newsock;
            $newclient = $newsock;
            $message = $frame->encode("new user ".$conn['id']." joined");
            foreach ($client as $key => $value) {
                socket_write($value['conn'], $message, strlen($message));
            }
            array_push($client, $conn);
            // var_dump($client);
            
        }
        

        
        if(count($client)){
            if ($newclient){
                if (($string = socket_read($newclient, 1024))){
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
                        socket_write($newclient, $response, mb_strlen($response));
                        $message = $frame->encode('your id is '.$client[count($client) - 1]['id']);
                        socket_write($newclient, $message, mb_strlen($message));
                        print_r('new user connection success'.PHP_EOL);
                        unset($string);
                        $newclient = null;
                    }
                }
            }

            foreach ($client as $key => $value) {
                if (!($string = socket_read($value['conn'], 1024))){

                }else{
                    if($string){
                            if($rawMessage = $frame->decode($string))
                                $message = $frame->encode(json_encode(Array('sender' => $value['id'],
                                                        'messsage' =>$rawMessage['payload'])));
                            else
                                $message = $frame->encode($string);   ///for netcat
                            foreach ($client as $k => $v) {
                                socket_write($v['conn'], $message, mb_strlen($message));
                            }
                        }
    
                    }
            
        }
        
        sleep(1);
        
    }
}
    socket_close($sock);

    
?>