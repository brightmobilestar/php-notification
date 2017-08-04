<?php 

    $deviceToken = $_REQUEST['token'];
    $message = $_REQUEST['message'];
    $title = $_REQUEST['title'];
    $num_of_badge = $_REQUEST['num_of_badge'];
    
    $passphrase = 'Notibrew';
    $path = 'Certificates-Dev-APN.pem';
    $ctx = stream_context_create();
    
    stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
    
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
    
    $fp = stream_socket_client( 'tls://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
    
    echo $deviceToken;

    if (!$fp)
    {
        echo "Error Ocurred";
        return false;
    } else {
        $body['aps'] = array(
                'alert' => array(
                'title'=>$title,
                'body'=>$message,
                // 'badge' => $num_of_badge
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