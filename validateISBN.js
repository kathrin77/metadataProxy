/**
 * Test the entered ISBN-13 by reducing the entered ISBN to numbers (or X) only, then matching against the regex pattern.
 * @param {type} isbn
 * @returns {Boolean} true or false
 */


function isValidISBN (isbn) {
    
    //Pattern: anything that is NOT (^) digits (\d) or X
    //Modifier: i=case-insensitive, g=global match
    isbn = isbn.replace(/[^\dX]/gi, '');
        
    //regex pattern for ISBN-13 (only numbers or x)
    var isbnpattern = /^(97(8|9))\d{9}(\d|X)$/i;
        
    return isbnpattern.test(isbn);
        
}