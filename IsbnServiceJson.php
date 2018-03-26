<?php

require_once 'IsbnService.php';

/**
 * Description of IsbnServiceJson
 *
 * @author K.Heim, after template from MBlock
 */
class IsbnServiceJson implements IsbnService {
    
    
    public function __construct() {
        
    }

    
    /**
     * Aufbau des RESTful Web Service-Client von OCLC zur Abfrage der Metadaten mittels ISBN
     * (Dienstmethode getMetadata)
     * 
     * @param type $isbn
     * @return type $jsonresult enthält das Abfrageresultat als String.
     */
    public function getServiceData($isbn) {
        $xisbn_url = "http://xisbn.worldcat.org/webservices/xid/isbn/" . $isbn . "?method=getMetadata&format=json&fl=*";
        $jresult = file_get_contents($xisbn_url); //gibt Resultat als String zurück
        return $jresult;       
        
    }      

}
