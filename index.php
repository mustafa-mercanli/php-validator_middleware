<?php

    
    $__SCHEMA = array("name"=>array("type"=>"alphaspace","required"=>true),
                      "surname"=>array("type"=>"str","required"=>true,"regex"=>"^[a-zA-Z\s]","custom_err"=>"Must be string bro"),
                      "birth_date"=>array("type"=>"datestr","required"=>true,"datefmt"=>"Y-m-d"),
                      "age"=>array("type"=>"int","nullable"=>true,"required"=>true),
                      "salary"=>array("type"=>"float","nullable"=>true));

    require './middleware.php';


   
    echo json_encode($__PARAMS);
?>