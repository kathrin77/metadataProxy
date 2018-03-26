<?php

require_once './functions.php';
require_once './IsbnService.php';
require_once './IsbnServiceJson.php';
require_once './My_MySQLi.php';

    //Prevalidate (stripslashes etc.)
    $prevalidatedIsbn = preValidate($_POST["isbn"]);
    
    
    
    try {
        
        //Validate ISBN:        
        if (validateIsbn($prevalidatedIsbn)) {
           
            //check local data:
            $result = checkMySQL($prevalidatedIsbn);
            
            //local data found:
            if (isset ($result)) { 
                  
                echo $result;
                return;
            } else {                
                
                try {
                //get data from OCLC webservice:
                $resultString = getMetadatafromOclc($prevalidatedIsbn);
                
                //if OCLC returns data:                
                if (isset($resultString)) {
                    $statOK = '"stat":"ok"';
                    
                    if (strpos($resultString, $statOK)) {
                        //store result locally
                        insertMetadataintoDB($prevalidatedIsbn, $resultString);
                    }
                                        
                    echo $resultString;
                    return;
                    }                

                } catch (Exception $ex) {
                    //oclc returns nothing or is unavailable
                    echo 'requestHandler: Exception: oclc gibt nichts zurück.';
                    return;

                }

            }
        
        } 
        
    } catch (Exception $ex) {
        //Fehlermeldung ausgeben(?) - throw new Exception("Validation Error", 666);
        echo "requestHandler: ISBN Validation error!";

    }
    



