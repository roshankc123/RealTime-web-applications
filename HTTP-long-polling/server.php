<?php
/*
    blocking mode: server gets blocked by the request. so, muliple client has difficulty accessing the server.
    whenever data is written to a, it is apparently pushed to client
    non blocking mode: PHP_CLI_SERVER_WORKERS=<no_of_client_you_make> php -S 127.0.0.1:8081 -t .
    the specified number of client gets connected, i used 4 as there are 4 clients 2 read and 2 write 
*/ 
set_time_limit(30);  //usually it is set to 100 to 300 for long pooling
error_reporting(0);
ob_implicit_flush();

    if($_GET['data']){
        file_put_contents($_GET['id'], $_GET['data']);
        echo $_GET['data'];
    }else{
        $read_id = $_GET['id'] == 'a' ?  'b' : 'a' ;
        while (true) {
            if($message = file_get_contents($read_id)){
                echo $message;
                file_put_contents($read_id, '');
                break;
            }else{
                sleep(2);
            }
        }
    }
    // while (true) {
    //     if($message = file_get_contents('a')){
    //         echo $message;
    //         file_put_contents('a', '');
    //         break;
    //     }else{
    //         for($i=0;$i<=10000;$i++){
    //             ///to delay the file read
    //         }
    //         continue;
    //     }
    // }
    
?>