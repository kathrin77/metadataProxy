<?php

require_once './functions.php';
require_once './IsbnService.php';
require_once './IsbnServiceJson.php';
require_once './My_MySQLi.php';

/**
 * requestHandler for metadataProxy:
 * 
 * The entered data is validated. If valid ISBN, the data is checked against local storage (mysql).
 * If ISBN is stored locally, the local data is returend (JSON), otherwise, 
 * data is searched on WorldCat through OCLC webservice.
 * If found and valid data, it is stored locally and returned, otherwise, 
 * the data status is returned only (invalidId if invalid ISBN, unknownID if ISBN not found on WorldCat).
 *
 * @author K.Heim
 */

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
                    echo 'requestHandler: Exception: oclc returns nothing.';
                    return;

                }

            }
        
        } 
        
    } catch (Exception $ex) {
        //error message
        echo "requestHandler: ISBN Validation error!";

    }
    



