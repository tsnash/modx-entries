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
$ENTRIES_where_delimiters = ‘\/%’; //delimiters to use when parsing where clauses (see ‘parseWhereClause’ function)
$ENTRIES_tables = array( //all of the tables in the database, the columns they each have and their types
	'index' => array(
	    'id' => 'i', 
	    'created' => 's', 
	    'updated' => 's', 
	    'year' => 'i',
	    'month' => 'i',
	    'day' => 'i',
	    'title' => 's', 
	    'author' => 's', 
	    'article' => 's'
    )
);

//********** Form/POST Settings **********//
$ENTRIES_key_columns = array( //maintain columns that are primary keys for unique row distinction
    'index' => 'id';
);
$ENTRIES_post_mode = 'post'; //name of POST data variable that holds preview/implant modes
$ENTRIES_preview_resource = '10'; //id of resource that calls PreviewEntry snippet
$ENTRIES_preview_new = 'new'; //value that should be indicated when previewing a new entry
$ENTRIES_preview_edit = 'edit'; //value that should be indicated when previewing edits to an entry
$ENTRIES_implant_resource = '11'; //id of resource that calls ImplantEntry snippet
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
