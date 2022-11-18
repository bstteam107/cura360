<?php 
    include('zoho.php');
    $zoho = new zoho();
    echo "testing....<br />";
    $auth = $zoho->getAuth();
    echo " <pre>";
    echo $auth;
    $result = $zoho->postData($auth, 'Bob','test', 'lol@lol.dk','adresse','by','postr','Danmark','Some comment');
    print_r($result);
 ?>