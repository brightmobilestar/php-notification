<?php 

    $deviceToken = $_REQUEST['token'];
    // $deviceToken = 'b571226b510e2af143cbefaa5687d39eab2f0ec1081013742b9815b5751d1090';
    $message = $_REQUEST['message'];
    $title = $_REQUEST['title'];
    // $message = "Test";
    

    $passphrase = 'Notibrew';
    $path = 'Certificates-Dev-APN.pem';
    $ctx = stream_context_create();
    
    stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
    
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
    
    // Open a connection to the APNS server
    //'ssl://gateway.push.apple.com:2195'
    // tls://gateway.sandbox.push.apple.com:2195
    // $fp = stream_socket_client( 'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
    $fp = stream_socket_client( 'tls://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
   
    if (!$fp)
    {
        echo "Error Ocurred";
        return false;
    } else {
        $body['aps'] = array(
                'alert' => array(
                'title'=>$title,
                'body'=>$message
            ),
            'sound' => 'BeerSound.wav',
            'Person' =>array(
                'userId'=>'test_id12345',
                'name'=>'Test name push',
                'image'=>'Test image'
            )
        );
         
        $body['message'] = 'notification_type';
        // Encode the payload as JSON
        $payload = json_encode($body);
         
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
         
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
         
        fclose($fp);
        return true;
    }


?>