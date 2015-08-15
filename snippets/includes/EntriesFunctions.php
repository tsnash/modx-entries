<?php

if(!function_exists(trimAfterLoop)) {

	function trimAfterLoop($loopedString, $numCharsToTrim, $stringToAppend = '') {

		if((int)$numCharsToTrim == $numCharsToTrim) {
			return substr($loopedString,0,strlen($loopedString)-$numCharsToTrim) . $stringToAppend;
		}
		else return $loopedString;
	}
}

if(!function_exists(parseWhereClause)) {
    
    function parseWhereClause($clause) {
        
        $output = '';
        
        //each delimiter can only be one character from the string specified in the settings file
        //don't forget to escape each character with a special meaning in regular expression brackets
        $c = preg_split('/[' . $ENTRIES_where_delimiters . ']/', $clause, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        
        //each delimiter should have its own case
        switch ($c[1]) {
            
            case '/':
                $output = $c[0] . '="' . $c[2] . '"';
                break;
            case '%':
                $output = ' RLIKE "([-a-zA-Z0-9, ]*,|^)' . $c[2] . '(,[-a-zA-Z0-9, ]*|$)"';
                break;
            default:
        }
        
        if(strlen($output)) $output .= ' AND '; 
        
        return $output;
    }
}

if(!function_exists(parseRow)) {

	function parseRow($table, &$row) {

		//if after retrieving data from the database and you have to modify it in some way do it here

		if($table == $ENTRIES_default_table) {
			//example of formatting timestamps prior to display
			if(isset($row['created'])) {
				$created = strtotime($row['created']);
				$updated = strtotime($row['updated']);
				$row['created'] = strftime('%A %B %d, %G',$created);
				$row['updated'] = ($created < $updated) ? '| Updated: ' . strftime('%m-%d-%Y %r %Z',$updated) : ''; 
			}
		}

	}
}


if(!function_exists(chooseChunk)) {
    
    function chooseChunk($chunkType, $tableName, array $row = array()) {
        
        $chunkName = '';
        
        //if you want chunks to be chosen based on a set of conditions set them up here
        
        $chunkName .= $chunkType; //default behavior
        
        return $chunkName;
        
    }
    
}

if(!function_exists(retrieveURL)) {

	function retrieveURL() {

		$url = $_GET['q'];

		//examples of ways to retrieve  using GET assuming page alias (q) is 'post'

		//example.com/post?id=0

		if(isset($_GET['id'])) {
			$url .= '?id=' . $_GET['id'];
		}

		/*
		//example.com/post?year=1970&month=1&day=1&id=0

		if(isset($_GET['year']) {
			$url .= '?year=' . $_GET['year'];
			if(isset($_GET['month'])) {
				$url .= '&month=' . $_GET['month'];
				if(isset($_GET['day'])) {
					$url .= '&day=' . $_GET['day'];
					if(isset($_GET['id'])) {
						$url .= '&id=' . $_GET['id'];
					}
				}
			}
		}
		*/

		/*
		//example.com/post/1970/1/1/0
		//NOTE: this method will require tweaking the .htaccess file to read the url as a query string

		if(isset($_GET['year']) {
			$url .= '/' . $_GET['year'];
			if(isset($_GET['month'])) {
				$url .= '/' . $_GET['month'];
				if(isset($_GET['day'])) {
					$url .= '/' . $_GET['day'];
					if(isset($_GET['id'])) {
						$url .= '/' . $_GET['id'];
					}
				}
			}
		}
		*/

		return $url;
	}
}

?>
