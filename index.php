
<!DOCTYPE html>
<!--
METADATEN-PROX: OCLC ISBN Webservice
Kathrin Heim, BibInfo16 Abschlussaufgabe 1
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Metadata Proxy</title>
        <link rel="stylesheet" type="text/css" href="layout.css"/>   
        
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script> 
        <script type="text/javascript" src="validateISBN.js"></script>

        <script>
            
            $(document).ready(function() {
                
                $.validator.addMethod("isbn", function(value, element) {
                    return isValidISBN(value);
                }, " Please enter a valid ISBN-13 Number. ");
                
                    //check if isbn is correct 
                    $("#myForm").validate({
                        debug: true,
                        rules: {
                            isbnInput: {
                                required: true, 
                                isbn: true
                            }
                            
                        }
                        
                    });
        

                $('#myForm').submit(function(event){                   
                    
                    $.ajax({
                        url: "requestHandler.php", 
                        method: "POST",
                        data: {
                            "isbn":$('#isbnInput').val() 
                        },
                        complete: function(response) {
                            var d = new Date();
                            var realTime = d.toLocaleDateString('de-DE');                            
                            $('.realtime').html('&bull; '+realTime);

                        },
                        success: function(response) {

                            var data = JSON.parse(response);
                            
                            if (data.stat !== 'ok') {
                                alert("Data not found: "+data.stat);
                                $('.output').css('visibility', 'hidden');
                            } else {

                                $('.output').css('visibility', 'visible');
                            }
                            
                            $('.mediatype').html("<img src='icons/"+data.list[0].form[0]+".png'>");
                            $('.isbn').html(data.list[0].isbn);
                            $('.title').html(data.list[0].title);
                            $('.author').html(data.list[0].author);
                            $('.year').html(data.list[0].year);
                            $('.publisher').html(data.list[0].publisher);
                            $('.city').html(data.list[0].city);                            
                            $('.url').html("<img src='icons/logo.png' alt='WorldCat' height='64'></br><a href="+
                                    data.list[0].url+" target='_blank'>View record on Worldcat</a>");

                        },
                        error: function(response) {
                            alert("ERROR: ");
                            $('.output').html(response);
                        }
                    });
                });

            });
        </script>
        
    </head>
    <body>
        <div class="logo">
            <h1 class="pagetitle">ISBN Metadata-Proxy</h1>
        </div>

        <form id='myForm'>
            <label for="isbnInput"></label>
            <input id="isbnInput" name="isbn" type="text" class="isbn">
            <input id= "submitbutton" type="submit" name="submitbutton" value="search ISBN in Worldcat">
        </form>
        
        <div class="output">
            <h1 class="record">Record: </h1>
            <div class="mediatype"></div>

            ISBN: <div class="isbn"></div>
            Title: <div class="title"></div>
            Author: <div class="author"></div>
            Year: <div class="year"></div>
            Publisher: <div class="publisher"></div>
            City: <div class="city"></div>            
            <div class="url"></div>            
                        
        </div>

        
        <div class="legend">
            <h2>Mediatypes:</h2>
            <img src='icons/AA.png' height="40px">&nbsp;Audio (AA)&nbsp;
            <img src='icons/BA.png' height="40px">&nbsp;Book (BA, BB, BC)&nbsp;
            <img src='icons/DA.png' height="40px">&nbsp;Digital (DA)&nbsp;
            <img src='icons/FA.png' height="40px">&nbsp;Video (FA, VA)&nbsp;
            
        </div>
        </br>
        <footer>
            <address>
                &copy; MetadataProxy by Kathrin Heim &bull; BibInfo16 <span class="realtime"></span>
            </address>
            <a href="https://icons8.com" target="blank">Icon pack by Icons8</a>
        </footer>


    </body>
</html>
