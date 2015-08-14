<?php

require 'includes/EntriesSettings.php';
require_once 'includes/EntriesFunctions.php';

$table = array_key_exists($table, $ENTRIES_tables) ? $mydb->escape($table) : $ENTRIES_default_table; //table entry will be sent to

$columnValues = array();

foreach ($ENTRIES_tables[$table] as $column => $type) {
	if(isset($_POST[$column])) $columnValues[$column] = $_POST[$column];
}

$output = $modx->parseChunk($ENTRIES_preview_chunk, $columnValues, '[+', '+]');

$output .= '\n<form name="entry" action="[~' . $ENTRIES_implant_resource .'~]" method="post">\n';
$output .= '<input type="hidden" name="' . $ENTRIES_implant_mode . '" value="' . $_POST[$ENTRIES_implant_mode] . '>\n';

foreach ($columnValues as $column => $value) {
	$output .= '<input type="hidden" name="' . $column . '" value="' . $value . '">\n';
}

$output .= '<input type="button" onclick="history.back();" value="Back">\n';
$output .= '<input type="submit" value="Submit">\n</form>';

return $output;
?>
