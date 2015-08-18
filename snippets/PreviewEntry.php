<?php

require 'includes/EntriesSettings.php';
require_once 'includes/EntriesFunctions.php';

$table = (isset($table) && array_key_exists($table, $ENTRIES_tables)) ? $table : $ENTRIES_default_table; //table entry will be sent to

//shows preview if all is good with _POST data
if ($formattedPost = formatColumns($table, $_POST)) {
    $row = array();
    
    foreach ($ENTRIES_tables[$table] as $column => $type) {
    	if(isset($formattedPost[$column])) $row[$column] = $formattedPost[$column];
    }
    
    //put form together for database submission
    $form = '\n<form name="entry" action="[~' . $ENTRIES_implant_resource .'~]" method="post">\n';
    $form .= '<input type="hidden" name="' . $ENTRIES_implant_mode . '" value="' . $formattedPost[$ENTRIES_implant_mode] . '>\n';
    foreach ($row as $column => $value) {
    	$form .= '<input type="hidden" name="' . $column . '" value="' . $value . '">\n';
    }
    $form .= '<input type="button" onclick="history.back();" value="Back">\n';
    $form .= '<input type="submit" value="Submit">\n</form>';
    
    parseRow($table, $row);
    $preview = $modx->parseChunk(chooseChunk($ENTRIES_preview_chunk, $table, $row), $row, '[+', '+]');
    
    $output = $preview .'\n'. $form;
    
}

else {
    $output = '<input type="button" onclick="history.back();" value="Back">\n';
}

return $output;
?>
