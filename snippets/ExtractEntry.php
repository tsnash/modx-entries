<?php

require 'includes/EntriesSettings.php';
require_once 'includes/EntriesFunctions.php';

/*

how to call this snippet
[[ExtractEntry? &table=`` &colWhere=`` &startEntry=`` &maxEntries=`` &order=`` &chunkType=`` &paginate=``]]

examples

table - name of table you'll be selecting from
&table=`table` selects from 'prefix_table' if the table prefix is 'prefix_'

colWhere - where clauses to be separated by and operators
&colWhere=`type/Blog subcat/Videos url/some-cats` changes to 
'WHERE type="Blog" AND subcat="Videos" AND url="some-cats"'

startEntry - first entry to retrieve from query starting from 0
&startEntry=`4` starts from retrieving the 5th entry

maxEntries - limits the total amount of entries to be retrieved
&maxEntries=`20` will only retrieve up to 20 entries

order - order by criteria for sorting entries
&order=`created DESC` orders entries starting with the latest timestamp from the created column

chunkType - this determines the chunk to send retrieved entries to for formatting
&chunkType=`Summary` sends each column from the row as a placeholder into the 'Summary' chunk

paginate - determines whether to allow links for separate entry pages (true/false)
&paginate=`1` will indicate to append links for changing pages
NOTE: page numbers are determined using _GET data
www.example.com/?p=2 will indicate to try retrieving the second page of entries

*/

//initialize database connection
$mydb = new DBAPI();
$mydb->config['host'] = $ENTRIES_host; 
$mydb->config['dbase'] = $ENTRIES_database;
$mydb->config['user'] = $ENTRIES_extract_user;
$mydb->config['pass'] = $ENTRIES_extract_pass;
$mydb->config['charset'] = $ENTRIES_charset;
$mydb->config['table_prefix'] = $ENTRIES_prefix;
$mydb->connect();


//set up parameter defaults
$table = (isset($table) && array_key_exists($table, $ENTRIES_tables)) ? $mydb->escape($table) : $ENTRIES_default_table; //table to retrieve entry from
$colWhere = isset($colWhere) ? $colWhere : ''; //MySQL WHERE command parameters
$order = isset($order) ? $mydb->escape($order) : $ENTRIES_default_order; //MySQL ORDER BY command to use
$startEntry = is_numeric($startEntry) ? $mydb->escape($startEntry) : '0'; //the first entry to extract
$maxEntries = is_numeric($maxEntries) ? $mydb->escape($maxEntries) : '0'; //number of entries to extract (0 means all)
$chunkType = isset($chunkType) ? $mydb->escape($chunkType) : $ENTRIES_entry_chunk; //chunk where entry data should be sent to
$paginate = isset($paginate) ? $paginate : ''; //determines whether to allow linking to pages
$p = (isset($_GET['p']) && (int)$_GET['p'] == $_GET['p']) ? $_GET['p'] : '1'; //validate page number


//calculation of page entries
if($paginate == 1 && $p > 1) {

	$startEntry = $startEntry + ($p - 1) * $maxEntries; //first entry to retrieve on page

}

//tables to select from
$qField = '';
foreach($ENTRIES_tables[$table] as $column => $type) {
	$qField .= $column . ', ';
}
$qField = trimAfterLoop($qField, 2); //removes trailing string ', '

//format from table reference
$qFrom = $mydb->config['table_prefix'] . $table;

//parse where clause(s)
$qWhere = '';
if(!empty($colWhere)) {

	$clauses = explode(' ',$colWhere);
	foreach($clauses as $clause) {

		$qWhere .=  parseWhereClause($clause);

	}
	$qWhere = trimAfterLoop($qWhere, 5); //removes trailing string ' AND '

}
 
//determine syntax of limit clause
$qLimit = ($startEntry != '0') ?  ($startEntry . ',' . $maxEntries) : (($maxEntries != 0) ? $maxEntries : '');


$result = $mydb->select($qField,$qFrom,$qWhere,$order,$qLimit);

//retrieve number of results
$count = $mydb->getRecordCount($result);

//terminates if no rows are present
if( $count < 1 ) {

	return $modx->getChunk($ENTRIES_no_results_chunk);

}

else {
	
	//prepare string for holding formatted data
	$output = '';

	//adds each individual row to final output string
	while($row = $mydb->getRow($result)) {

		parseRow($row);
		$output .= $modx->parseChunk(, $row, '[+', '+]');

	}
	
	if($paginate == 1) {
		
		//calculate total entries for determining what page links to allow
		$total = $mydb->getRecordCount($mydb->select($qField,$qFrom,$qWhere));
	
		//figure out url
		$url = retrieveURL();
	
		//format links and add them to end of page html
		$pStyle = (strpos($url, '?') !== false) ? '&p=' : '?p=';
		$pages['next'] = ($startEntry - $maxEntries >= 0) ? '<a href="' . $url . $pStyle . --$p . '">' . $ENTRIES_next . '</a>' : $ENTRIES_next;
		$pages['prev'] =  ($startEntry + $maxEntries < $total) ? '<a href="' . $url . $pStyle . ++$p . '">' . $ENTRIES_prev . '</a>' : $ENTRIES_prev;
		$output .= $modx->parseChunk($ENTRIES_pages_chunk, $pages, '[+', '+]');

	}
}

$mydb->disconnect();
return $output;

?>
