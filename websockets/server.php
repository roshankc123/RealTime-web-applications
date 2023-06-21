<?php
    /***
     *       
     *      used reference from https://www.php.net/manual/en/function.socket-select.php
     *      run this code in a terminal using php raw_socket.php or anyway you like
     *      it is a example of non-blocking IO thats continuosly read the socket and broadcast the message
     *      this is the example of multi chat application using raw socket and same code is valid in c/c++with little tweek  
     *      upgraded to v1: added socket_select to check socket event for user connection and disconnection, and simplified code
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
    
    $port = 6317;
    
    // Create a TCP Stream socket
    
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    // reuse the port

    socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);

    // Non block socket type

    socket_set_nonblock($sock);
    
    // Bind the socket to an address/port
    
    socket_bind($sock, $address, $port) or die('Could not bind to address'.PHP_EOL);

    // printf('socket created'.PHP_EOL);
    
    
    
    // Start listening for connections
    
    socket_listen($sock);

    ///client array contains all information about client
    $client = array(
        array(
            'id' => 0,
            'conn' => $sock,
            'type' => 'raw'     ////type defines if the user is from terminal or browser(default raw means terminal)
        )
    );  // contain a list of associative array {'id', 'connection handler'}

    $clientConn = array($sock);  ///stores connection object only for socket_select

    $clientSn = count($clientConn);  ///serial number of client, always +1 when new client added, and don't -1 ever

    $write = array();

    $expect = array();

    // Loop continuously
    while (true)
    
    {
        //to preserve the $client from mutated by socket_select()
        $read = $clientConn;

        //  @ to ignore errors
        if(@socket_select($read, $write, $expect, 0)){
            if($newclient = @socket_accept($sock)){

                $conn['id'] = $clientSn;

                $conn['conn'] = $newclient;

                $conn['type'] = 'raw';

                $client[] = $conn;

                $clientConn[] = $newclient;

                $clientSn++;

                // echo 'new client socket '.$conn['id'].' connected'.PHP_EOL;
                }
            }

        foreach ($client as $read_socket) {

            //first is binding socket, so ignore it
            if($read_socket['conn'] != $sock){
                // read the incoming message fro socket but don't wait
                if(@socket_recv($read_socket['conn'], $recv_data, 4096, MSG_DONTWAIT) === 0){
                    
                    unset($client[$read_socket['id']]);

                    unset($clientConn[$read_socket['id']]);

                    // echo 'client '.$read_socket['id'].' disconnected'.PHP_EOL;

                    $message = json_encode(
                        array(
                            'total' => $clientSn,
                            'active' => count($clientConn),
                            'message' => "user ".$read_socket['id']." disconnected"
                        )
                    );

                    broadcast('system', $client, $message, $frame);

                }else{
                    if($recv_data){

                        if(preg_match('/Sec-WebSocket-Key: (.*)\r\n/', $recv_data, $matches)){
                            
                            // var_dump($read_socket);
                            $key = base64_encode(sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
                            // echo $key;
                            $response = "HTTP/1.1 101 Switching Protocols\r\n";
                            $response .= "Upgrade: websocket\r\n";
                            $response .= "Connection: upgrade\r\n";
                            $response .= "Sec-WebSocket-Accept: $key\r\n";
                            $response .= "\r\n";

                            socket_write($read_socket['conn'], $response, strlen($response));
                            
                            ///update the type of connection (etirher fromtermianl or from browser)
                            $client[$read_socket['id']]['type'] = 'websocket';
                            
                            $message = json_encode(
                                array(
                                    'total' => $clientSn,
                                    'active' => count($clientConn),
                                    'message' => 'new user '.$read_socket['id'].' connected from browser'
                                )
                            );

                            // echo $response;
                            broadcast('system', $client, $message, $frame);
                            
                        }else{

                            // var_dump($recv_data);
                            // var_dump($read_socket);
                            if($read_socket['type'] == 'websocket'){
                                $message = $frame->decode($recv_data)['payload'];
                            }else{
                                $message = $recv_data;
                            }

                            // var_dump($message);

                            broadcast($read_socket['id'], $client, $message, $frame);

                            // echo $message.PHP_EOL;
                        }
                        

                    }
                        
                }
            }
        }
        // sleep(1);
    }

    socket_close($sock);


function broadcast($sender, $receiver, $message, $frame){
    $tmpJson = json_encode(array('sender' => $sender,'message' => $message)
    );
    // echo $tmpJson;
    $message = $frame->encode($tmpJson);

    foreach ($receiver as $key => $value) {
        socket_write($value['conn'], $message, strlen($message));
    }

}
    
?>