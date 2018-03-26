

<?php

require_once './My_MySQLi.php';
require_once './IsbnService.php';
require_once './IsbnServiceJson.php';

/**
 * Prevalidates ISBN by removing whitespace, backslashes, special characters and hyphens.
 * @param String $string
 * @return String
 */
function preValidate($string) {
	//remove whitespaceChars
	$trimmed = trim($string);

	//remove backslashes and html-characters
	$stripped = stripcslashes($trimmed);
        $htmlstripped = htmlspecialchars($stripped);
        
        //remove hyphens        
        $result = preg_replace('/-/', '', $htmlstripped);

	return $result;
}

/**
 * Validates ISBN with a regular expression
 * throws an exception, if validation fails.
 * @param type $string
 * @return boolean
 * @throws Exception
 */


function validateIsbn($string) {
    
    $pattern = '/^[0-9]{12}[0-9xX]$/';
    
    if (preg_match($pattern, $string) && !empty($string)) {
	return true;
    }
	
    throw new Exception("validateIsbn: Validation Error", 666);
}

/**
 * checks local storage (mysql) if data already exists
 * @param type $isbn
 * @return boolean
 */

function checkMySQL($isbn) {
    
    $mysql = new My_MySQLi("localhost", "root", "", "metadataproxy");
    $query = "SELECT * FROM metadata WHERE isbn = '".$isbn."';";

    $result = $mysql->query($query);    

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_row();
        return $row[1];
  

    } else {
        return NULL; 
        
    }
}

/** 
 * Gets metadata from OCLC with IsbnServiceJSON.
 * @param type $isbn
 * @return JSON-String
 */

function getMetadatafromOclc($isbn) {
    $oclcData = new IsbnServiceJson($isbn);
    $resultString = $oclcData->getServiceData($isbn);
    return $resultString;    
    
}


/**
 * inserts the new data from OCLC to the local storage (mysql)
 * @param type $metadata, $isbn
 */

function insertMetadataintoDB($isbn, $metadata) {
    
    //$metadata bereinigen: alle ' ersetzen mit UTF-8-Zeichen
    $specialCharacter = array("'");
    $replaceCharacter = array("\xE2\x80\x99");
    $cleanedMetadata = str_replace($specialCharacter,$replaceCharacter, $metadata);
    
    $mysql = new My_MySQLi("localhost", "root", "", "metadataproxy"); 
        
    $query = "INSERT INTO `metadata` "
            . "(`isbn`, `jsonString`) "
            . "VALUES ('".$isbn."','".$cleanedMetadata."');";
    
    $mysql->query($query);      
        
        
    }


