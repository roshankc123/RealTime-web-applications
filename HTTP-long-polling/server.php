<?php
/*
    message from a is stored in a
    a retrieve message from b, upon retrieveing it empties b
    and vice versa
*/ 
set_time_limit(5);  //usually it is set to 100 to 300 for long pooling
error_reporting(0);
    // if($_GET['data']){
    //     file_put_contents($_GET['key'], $_GET['data']);
    //     echo '';
    // }else{
    //     $key = $_GET['key'] == 'a' ?  'b' : 'a' ;
    //     while (true) {
    //         if($message = file_get_contents($key)){
    //             echo $message;
    //             file_put_contents($key, '');
    //             break;
    //         }else{
    //             for($i=0;$i<=10000;$i++){
    //                 ///to delay the file read
    //             }
    //         }
    //     }
    // }
    while (true) {
        if($message = file_get_contents('a')){
            echo $message;
            file_put_contents('a', '');
            break;
        }else{
            for($i=0;$i<=10000;$i++){
                ///to delay the file read
            }
            continue;
        }
    }
    
?>