<?php
    /***
     *       
     *      used reference from https://www.php.net/manual/en/ref.sockets.php
     *      run this code in a terminal using php raw_socket.php or anyway you like
     *      it is a example of non-blocking IO thats continuosly read the socket and broadcast the message
     *      this is the example of multi chat application using raw socket and same code is valid in c/c++with little tweek  
     * 
    ****/

    //for debugging set this to 1
    ini_set('error_reporting', 0);
    
    
    // Set time limit to indefinite execution(no timeout)
    
    set_time_limit (0);
    
    // Set the ip and port we will listen on
    
    $address = '127.0.0.1';
    
    $port = 6901;
    
    // Create a TCP Stream socket
    
    $sock = socket_create(AF_INET, SOCK_STREAM, 0);
    
    // Bind the socket to an address/port
    
    socket_bind($sock, $address, $port) or die('Could not bind to address'.PHP_EOL);

    printf('socket created'.PHP_EOL);
    
    
    
    // Start listening for connections
    
    socket_listen($sock);
    
    
    
    // Non block socket type
    
    socket_set_nonblock($sock);
    

    $client = [];  // contain a list of associative array {'id', 'connection handler'}

    // Loop continuously
    while (true)
    
    {
    
        if ($newsock = @socket_accept($sock))
    
        {

            $conn['id'] = count($client);

            $conn['conn'] = $newsock;

            array_push($client, $conn);

            foreach ($client as $key => $value) {

                printf('new client '.$value['id'].' connected'.PHP_EOL);

                send($conn['id'], $value['conn'], 'new client connected '.$conn['id'].PHP_EOL);

            }
        }
        
    
        if (count($client))
        
        {
    
            foreach ($client as $key => $value)
    
            {
    
                ///send empty string to close the connection, eg. echo '' | nc 127.0.0.1 6091
                if (@socket_recv($value['conn'], $string, 1024, MSG_DONTWAIT) === 0)
    
                {
                    $message = $value['id'].' closed the connection'.PHP_EOL;

                    foreach ($client as $k => $v) {

                        send($value['id'], $v['conn'], $message);

                    }
                    
                    socket_close($sock);

                    printf($message);

                    exit;
                }
    
                else
    
                {
    
                    if ($string)
    
                    {

                        foreach ($client as $k => $v) {

                            send($value['id'], $v['conn'], $string);

                        }

                        printf($value['id'].' to all : '.$string);
                        
                    }
    
                }
    
            }
    
        }
    
        sleep(1);   ///save the power
    
    }
    
    
    
    // Close the master sockets
    socket_close($sock);

    ///send the message to specified socket
    function send($sender, $receiver, $message){

        $message_f = "$sender> $message";

        socket_write($receiver, $message_f, strlen($message_f)).chr(0);

    }
    
?>