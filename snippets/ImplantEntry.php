<?php

require 'includes/EntriesSettings.php';
require_once 'includes/EntriesFunctions.php';


$table = (isset($table) && array_key_exists($table, $ENTRIES_tables)) ? $mydb->escape($table) : $ENTRIES_default_table; //table to send entry to

$mysqli = new mysqli($ENTRIES_host, $ENTRIES_implant_user, $ENTRIES_implant_pass, $ENTRIES_database);

if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

$prepare = array('query' => '', 'bindTypes' => '', 'bindValues' => array(), 'bindParams' => array());

//code to be used for preparing query for new entry
if ($_POST[$ENTRIES_implant_mode] == $ENTRIES_implant_new) {

	//initial query text
	$queryLHS = 'INSERT INTO ' . $ENTRIES_prefix . $table . ' (';
	$queryRHS = 'VALUES (';

	//get column data from POST array
	foreach ($ENTRIES_tables[$table] as $column => $type) {
		if(isset($_POST[$column])) { 
			$queryLHS .= $column . ', ';
			$queryRHS .= '?, ';
			$prepare['bindTypes'] .= $type;
			$prepare['bindValues'][] = $_POST[$column];

		}
	}

	$prepare['query'] = trimAfterLoop($queryLHS, 2, ') ') . trimAfterLoop($queryRHS, 2, ')');

}

//code to be used for preparing query for edting an entry
elseif ($_POST[$ENTRIES_implant_mode] == $ENTRIES_implant_edit) {
	
	//initial query text
	$queryLHS = 'UPDATE ' . $ENTRIES_prefix . $table . 'SET ';
	$queryRHS = 'WHERE ';

	//get column data from POST array
	foreach ($ENTRIES_tables[$table] as $column => $type) {
		if(isset($_POST[$column])) {
			if ($column == $ENTRIES_unique_column) {
				$queryRHS .= $column . '=?';
			}
			else {
				$queryLHS .= $column . '=?, ';
				$prepare['bindTypes'] .= $type;
				$prepare['bindValues'][] = $_POST[$column];
			}
		}
	}

	$prepare['query'] = trimAfterLoop($queryLHS, 2, ' ') . $queryRHS;
	
	$prepare['bindTypes'] .= $ENTRIES_tables[$table][$ENTRIES_unique_column];
	$prepare['bindValues'] .= $_POST[$ENTRIES_unique_column];
	
}

//prepare query
if($stmt = $mysqli->prepare($prepare['query'])) {
	
	//elaborate binding scheme
	$prepare['bindParams'][] = &$prepare['bindTypes'];
	foreach($prepare['bindValues'] as &$value) { 
	    $prepare['bindParams'] = $value;
	}
	unset($value); //just in case
	call_user_func_array(array($stmt, 'bind_param'), $prepare['bindParams']);

	if(!$stmt->execute()) printf("Error: %s.\n", $stmt->error);
	else echo "Database updated successfully!<br>";

	$stmt->close();

}

else throw new ErrorException($mysqli->error, $mysqli->errno);

$mysqli->close();

return $modx->getChunk($ENTRIES_implant_success);
?>
