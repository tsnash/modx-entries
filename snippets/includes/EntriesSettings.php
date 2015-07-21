<?php
//********** Database Settings **********//
$ENTRIES_host = ''; //database host
$ENTRIES_database = ''; //database name
$ENTRIES_extract_user = ''; //user with SELECT access to database
$ENTRIES_extract_pass = ''; //their password
$ENTRIES_implant_user = ''; //user with INSERT and UPDATE access to database
$ENTRIES_implant_pass = ''; //their password
$ENTRIES_charset = 'utf8'; //character set used by the database
$ENTRIES_prefix = 'data_'; //prefix of the table where data will be stored
$ENTRIES_default_table = 'index'; //default table to be queried
$ENTRIES_default_order = 'created DESC'; //default sorting of selected queries
$ENTRIES_columns = array( //all columns used in the database and their types
	'id' => 'i', 
	'created' => 's', 
	'updated' => 's', 
	'year' => 'i',
	'month' => 'i',
	'day' => 'i',
	'title' => 's', 
	'author' => 's', 
	'article' => 's'
);

//********** Form/POST Settings **********//
$ENTRIES_unique_column = 'id'; //MUST be in $ENTRIES_columns array or updating entries won't work
//$ENTRIES_key_columns = array(); //might implement this instead to allow use of multiple columns
$ENTRIES_implant_resource = '20'; //resource that calls ImplantEntry snippet
$ENTRIES_implant_mode = 'implant'; //name of POST data variable that holds implant mode
$ENTRIES_implant_new = 'implant'; //value that should be indicated when implanting a new entry
$ENTRIES_implant_edit = 'update'; //value that should be indicated when updating an entry


//********** Important Chunks **********//
$ENTRIES_no_results_chunk = 'NoResults'; //used when no results can be pulled from database
$ENTRIES_pages_chunk = 'Pages'; //parse page links when pagination is enabled
$ENTRIES_preview_chunk = 'Entry'; //preview entries before submitting to database
$ENTRIES_implant_success = 'ReturnToHome'; //displayed after ImplantEntry completes successfully
$ENTRIES_entry_chunk = 'Entry'; //parsed with row of entry data from database

//********** Pagination Links **********//
$ENTRIES_next = '&lt;&lt;'; //HTML for next page link
$ENTRIES_prev = '&gt;&gt;'; //HTML for previous page link
?>