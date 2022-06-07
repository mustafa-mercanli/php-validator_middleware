<?php


class Validator{
    public function __construct($schema){
        $this->schema = $schema;
        $this->schema_keys = array_keys($schema);
    }

    private $schema_keys;
    private $data_keys;
    private $error_list = array();

    public function errors(){
        return count($this->error_list) > 0 ? $this->error_list :null;
    }

    public function validate($data){
        $this->error_list = array();

        $data = json_decode(json_encode($data),true);
        
        $this->data_keys = array_keys($data);


        foreach ($this->data_keys as $key){
            if (in_array($key,$this->schema_keys)){
                "No problem";
            }
            else{
                array_push($this->error_list,array("key"=>$key,"err"=>"Unknown field"));
            }
            
        }

        foreach ($this->schema as $schema_key=>$schema_value){
            $err = array_key_exists("custom_err",$schema_value) ? $schema_value["custom_err"] : null;

            if (in_array($schema_key,$this->data_keys)){
                $data_value = $data[$schema_key];
                if (array_key_exists("nullable",$schema_value) && $schema_value["nullable"] && $data_value == null){
                    "No problem";
                }
                else{
                    if (array_key_exists("regex",$schema_value)){
                        if (!preg_match( "/".$schema_value["regex"]."/", $data_value )){
                            array_push($this->error_list,array("key"=>$schema_key,"err"=>$err ? $err : sprintf("Regex not matched: %s",$schema_value["regex"])));
                            continue;
                        }
                    }
                    if ($schema_value["type"] == "str" && gettype($data_value)!="string"){
                        array_push($this->error_list,array("key"=>$schema_key,"err"=>$err ? $err : "Must be string value"));
                        continue;
                    }
                    if ($schema_value["type"] == "int" && !preg_match( $this->regex_map["int"], $data_value )){
                        array_push($this->error_list,array("key"=>$schema_key,"err"=>$err ? $err : "Must be integer value"));
                        continue;
                    }
                    if ($schema_value["type"] == "float" && !preg_match( $this->regex_map["float"], $data_value )){
                        array_push($this->error_list,array("key"=>$schema_key,"err"=>$err ? $err : "Must be float value"));
                        continue;
                    }
                    if ($schema_value["type"] == "alpha" && !preg_match( $this->regex_map["alpha"], $data_value )){
                        array_push($this->error_list,array("key"=>$schema_key,"err"=>$err ? $err : "Must be alphabetic"));
                        continue;
                    }
                    if ($schema_value["type"] == "alphaspace" && !preg_match( $this->regex_map["alphaspace"], $data_value )){
                        array_push($this->error_list,array("key"=>$schema_key,"err"=>$err ? $err : "Must be alphabetic with spaces"));
                        continue;
                    }
                    if ($schema_value["type"] == "datestr"){
                        $datefmt = array_key_exists("datefmt",$schema_value) ? $schema_value["datefmt"] : "Y-m-d";
                        try{
                            $d = new DateTime($data_value);
                            if (strcmp(sprintf("%s",$d->format($datefmt)),sprintf("%s",$data_value))){
                                throw new Exception();
                            }
                        }
                        catch (Exception $e){
                            array_push($this->error_list,array("key"=>$schema_key,"err"=>$err ? $err : sprintf("Date must formatted: %s",$datefmt)));
                            continue;
                        }
                    }
                }
            }
            else {
                if (array_key_exists("required",$schema_value) && $schema_value["required"]){
                array_push($this->error_list,array("key"=>$schema_key,"err"=>"Required field"));
                continue;
                }
            }
        }

    }

    private $regex_map = array(
                                    "int"=>"/^\d+$/",
                                    "float"=>"/^[+-]?([0-9]*[.])?[0-9]+$/",
                                    "alpha"=>"/^[a-zA-Z]+$/",
                                    "alphaspace"=>"/^[a-zA-Z\s]+$/"
                                    );

    
}

?>