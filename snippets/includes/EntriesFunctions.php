<?php

if(!function_exists(trimAfterLoop)) {

	function trimAfterLoop($loopedString, $numCharsToTrim, $stringToAppend = '') {

		if((int)$numCharsToTrim == $numCharsToTrim) {
			return substr($loopedString,0,strlen($loopedString)-$numCharsToTrim) . $stringToAppend;
		}
		else return $loopedString;
	}
}

if(!function_exists(parseRow)) {

	function parseRow(&$row) {

		//if after retrieving data from the database and you have to modify it in some way do it here

		//example of formatting timestamps prior to display
		if(isset($row['created'])) {
			$created = strtotime($row['created']);
			$updated = strtotime($row['updated']);
			$row['created'] = strftime('%A %B %d, %G',$created);
			$row['updated'] = ($created < $updated) ? '| Updated: ' . strftime('%m-%d-%Y %r %Z',$updated) : ''; 
		}

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
