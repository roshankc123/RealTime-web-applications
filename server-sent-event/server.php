<?php
/*
    this server can't be accessed by multiple clients
    this is fast and no redundant request is to be opened by client, 
    on one open connection server can send muliplt events and messages
    whenever data is written to a, it is pushed to client as a event data
    server sent event is unidirectional ( from server to client )
*/ 
set_time_limit(30);
error_reporting(0);
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
$message = 'hello';

    while(true){
        if($message = file_get_contents('a')){
            // echo 'event: hello\n';
            echo "data: ".$message;
            echo "\n\n";
            file_put_contents('a', ''); 
        }
        ob_end_flush();
        flush();
        for($i=0;$i<5000;$i++){

        }
    }
    
// }
    

?>