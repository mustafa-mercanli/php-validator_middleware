# PHP-validator_middleware

## Goal:
This middleware includes a schema validator to force the requester to send valid paremeters and values. This middleware also define a object named $__PARAMS that includes whatever client send GET, POST or BODY parameters.

## Usage:
```php
$__SCHEMA = array("name"=>array("type"=>"alphaspace","required"=>true),
                  "surname"=>array("type"=>"str","required"=>true,"regex"=>"^[a-zA-Z\s]","custom_err"=>"Must be string bro"),
                  "birth_date"=>array("type"=>"datestr","required"=>true,"datefmt"=>"Y-m-d"),
                  "age"=>array("type"=>"int","nullable"=>true,"required"=>true),
                  "salary"=>array("type"=>"float","nullable"=>true));

require './middleware.php';

echo json_encode($__PARAMS);
```