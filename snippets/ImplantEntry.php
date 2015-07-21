<?php

require 'EntriesSettings.php';
require_once 'EntriesFunctions.php';


$tbl = isset($tbl) ? $mydb->escape($tbl) : $ENTRIES_default_table; //table to send entry to

$mysqli = new mysqli($ENTRIES_host, $ENTRIES_implant_user, $ENTRIES_implant_pass, $ENTRIES_database);

if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

$prepare = array('query' => '', 'bindTypes' => '', 'bindParams' => '');

//code to be used for preparing query for new entry
if ($_POST[$ENTRIES_implant_mode] == $ENTRIES_implant_new) {

	//initial query text
	$queryLHS = 'INSERT INTO ' . $ENTRIES_prefix . $tbl . ' (';
	$queryRHS = 'VALUES (';

	//get column data from POST array
	foreach ($ENTRIES_columns as $column => $type) {
		if(isset($_POST[$column])) { 
			$queryLHS .= $column . ', ';
			$queryRHS .= '?, ';
			$prepare['bindTypes'] .= $type;
			$prepare['bindParams'] .= $_POST[$column] . ', ';

		}
	}

	$prepare['query'] = trimAfterLoop($queryLHS, 2, ') ') . trimAfterLoop($queryRHS, 2, ')');
	$prepare['bindParams'] = trimAfterLoop($prepare['bindParams'], 2)

}

//code to be used for preparing query for edting an entry
elseif ($_POST[$ENTRIES_implant_mode] == $ENTRIES_implant_edit) {
	
	//initial query text
	$queryLHS = 'UPDATE ' . $ENTRIES_prefix . $tbl . 'SET ';
	$queryRHS = 'WHERE ';

	//get column data from POST array
	foreach ($ENTRIES_columns as $column => $type) {
		if(isset($_POST[$column])) {
			if ($column == $ENTRIES_unique_column) {
				$queryRHS .= $column . '=?';
			}
			else {
				$queryLHS .= $column . '=?, ';
				$prepare['bindTypes'] .= $type;
				$prepare['bindParams'] .= $_POST[$column] . ', ';
			}
		}
	}

	$prepare['query'] = trimAfterLoop($queryLHS, 2, ' ') . $queryRHS;
	$prepare['bindTypes'] .= $ENTRIES_columns[$ENTRIES_unique_column];
	$prepare['bindParams'] .= $_POST[$ENTRIES_unique_column];
}

if($stmt = $mysqli->prepare($prepare['query'])) {

	$stmt->bind_param($prepare['bindTypes'],$prepare['bindParams']);

	if(!$stmt->execute()) printf("Error: %s.\n", $stmt->error);
	else echo "Database updated successfully!<br>";

	$stmt->close();


}

else throw new ErrorException($mysqli->error, $mysqli->errno);

$mysqli->close();

return $modx->getChunk($ENTRIES_implant_success);
?>