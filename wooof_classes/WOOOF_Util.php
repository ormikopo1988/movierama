<?php 

// Utility functions

class WOOOF_Util {
	const _ECP = 'WUT';	// Error Code Prefix
	
	/***************************************************************************/
	/***************************************************************************/

	/**
	 * Better GI than print_r or var_dump -- but, unlike var_dump, you can only dump one variable.
	 * Added htmlentities on the var content before echo, so you see what is really there, and not the mark-up.
	 *
	 * Also, now the output is encased within a div block that sets the background color, font style, and left-justifies it
	 * so it is not at the mercy of ambient styles.
	 *
	 * Inspired from:     PHP.net Contributions
	 * Stolen from:       [highstrike at gmail dot com]
	 * Modified by:       stlawson *AT* JoyfulEarthTech *DOT* com
	 * 
	 * antonis: only public properties are shown/recursed...
	 * 
	 * @param mixed $var  -- variable to dump
	 * @param string $var_name  -- name of variable (optional) -- displayed in printout making it easier to sort out what variable is what in a complex output
	 * @param string $indent -- used by internal recursive call (no known external value)
	 * @param unknown_type $reference -- used by internal recursive call (no known external value)
	 */
	public static
	function do_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
	{
		$do_dump_indent = "<span style='color:#666666;'>|</span> &nbsp;&nbsp; ";
		$reference = $reference.$var_name;
		$keyvar = 'the_do_dump_recursion_protection_scheme'; $keyname = 'referenced_object_name';
	
		$out = '';
		
		// So this is always visible and always left justified and readable
		$out .= "<div style='text-align:left; background-color:white; font: 10px monospace; color:black;'>";
	
		if (is_array($var) && isset($var[$keyvar]))
		{
			$real_var = &$var[$keyvar];
			$real_name = &$var[$keyname];
			$type = ucfirst(gettype($real_var));
			$out .= "$indent$var_name <span style='color:#666666'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
		}
		else
		{
			$var = array($keyvar => $var, $keyname => $reference);
			$avar = &$var[$keyvar];
	
			$type = ucfirst(gettype($avar));
			if($type == "String") $type_color = "<span style='color:green'>";
			elseif($type == "Integer") $type_color = "<span style='color:red'>";
			elseif($type == "Double"){ $type_color = "<span style='color:#0099c5'>"; $type = "Float"; }
	        elseif($type == "Boolean") $type_color = "<span style='color:#92008d'>";
	        elseif($type == "NULL") $type_color = "<span style='color:black'>";
	
	        if(is_array($avar))
	        {
	       		$count = count($avar);
	       		$out .= "$indent" . ($var_name ? "$var_name => ":"") . "<span style='color:#666666'>$type ($count)</span><br>$indent(<br>";
	            $keys = array_keys($avar);
	            foreach($keys as $name)
	            {
		            $value = &$avar[$name];
		            $out .= self::do_dump($value, "['$name']", $indent.$do_dump_indent, $reference);
	            }
	            $out .= "$indent)<br>";
	         }
	         elseif(is_object($avar))
	         {
	            $out .= "$indent$var_name <span style='color:#666666'>$type</span><br>$indent(<br>";
	            foreach($avar as $name=>$value) {
	            	$out .= self::do_dump($value, "$name", $indent.$do_dump_indent, $reference);
	            }
	            $out .= "$indent)<br>";
	         }
	         elseif(is_int($avar)) $out .= "$indent$var_name = <span style='color:#666666'>$type(".strlen($avar).")</span> $type_color".htmlentities($avar)."</span><br>";
           	 elseif(is_string($avar)) $out .= "$indent$var_name = <span style='color:#666666'>$type(".strlen($avar).")</span> $type_color\"".htmlentities($avar)."\"</span><br>";
           	 elseif(is_float($avar)) $out .= "$indent$var_name = <span style='color:#666666'>$type(".strlen($avar).")</span> $type_color".htmlentities($avar)."</span><br>";
           	 elseif(is_bool($avar)) $out .= "$indent$var_name = <span style='color:#666666'>$type(".strlen($avar).")</span> $type_color".($avar == 1 ? "TRUE":"FALSE")."</span><br>";
   			 elseif(is_null($avar)) $out .= "$indent$var_name = <span style='color:#666666'>$type(".strlen($avar).")</span> {$type_color}NULL</span><br>";
         	 else $out .= "$indent$var_name = <span style='color:#666666'>$type(".strlen($avar).")</span> ".htmlentities($avar)."<br>";
	
	         $var = $var[$keyvar];
		}
	
			$out .= "</div>";
			
			return $out;
	}	// do_dump
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * Translates a camel case string into a string with
	 * underscores (e.g. firstName -> first_name)
	 *
	 * @param string $str String in camel case format
	 * @return string $str Translated into space/separator format
	 */
	public static
	function fromCamelCase($str, $sep=' ', $capitals=true) {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "' . $sep . '" . strtolower($c[1]);');
		$res = preg_replace_callback('/([A-Z])/', $func, $str);
		$res = trim( str_replace('_', $sep, $res), $sep );
		if ( $capitals ) { $res = ucwords($res); }
		return $res;
	}
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * Translates a string with underscores
	 * into camel case (e.g. first_name -> firstName)
	 *
	 * @param string $str String in underscore/separator format
	 * @param char $sep Default value '_'
	 * @param bool $capitalise_first_char If true, capitalise the first char in $str
	 * @return string $str translated into camel caps
	 */
	public static
	function toCamelCase($str, $sep='_', $capitalise_first_char = false) {
		if($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/'.$sep.'([a-z])/', $func, $str);
	}
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	// TODO: Hastily copied from wooof_admin and dbManager scripts
	// TODO: Do not create insert statements for Views!!!
	// TODO: Fix out overflow ???
	//
	public static
	function backupDatabase( WOOOF $wo ) {
		ob_clean();
		ob_start();
				
		$tR=$wo->db->query("show tables"); //get all table names
		while($t=$wo->db->fetchRow($tR)) //and for each one
		{
			$lr=$wo->db->query("select tableName from __tableMetaData where LOWER(tableName)='". strtolower($t[0]) ."'"); // get the description
			if (mysqli_num_rows($lr))
			{
				$l=@$wo->db->fetchRow($lr);
				$t[0]=$l[0];
			}
			if ($t[0]=='__columnmetadata')
			{
				$t[0]='__columnMetaData';
			}
			if ($t[0]=='__tablemetadata')
			{
				$t[0]='__tableMetaData';
			}
			if ($t[0]=='__dblog')
			{
				$t[0]='__dbLog';
			}
			if ($t[0]=='__externalfiles')
			{
				$t[0]='__externalFiles';
			}
			if ($t[0]=='__userrolerelation')
			{
				$t[0]='__userRoleRelation';
			}
			if ($t[0]=='__userpaths')
			{
				$t[0]='__userPaths';
			}if ($t[0]=='__bannedips')
			{
				$t[0]='__bannedIPs';
			}
			echo  "drop table if exists `". $t[0] ."`;\r\n";
			$table_cr_result=$wo->db->query("show create table `". $t[0] . "`" );
			$table_cr=$wo->db->fetchRow($table_cr_result);
			echo  str_replace(strtolower($t[0]), $t[0], $table_cr[1]) .";\r\n";
			//echo $table_cr[1] ."<br>";
			//echo "<font face=verdana size=4>Backing up : <font color=red>" .$l[0]; // output a user friendly mesage for people to be patient
			//$columns=""; // empty the columns aray that will hold the column names
			$fr=$wo->db->query("show columns from `". $t[0] . "`" ); // get the table column names
			unset($columns);
			while($f=$wo->db->fetchRow($fr)) { $columns[]=$f[0]; }// and stick them on the array
			$iterations=count($columns); // how many columns for this table
			
			$isView = $wo->db->getSingleValueResult("select isView from __tableMetaData where tableName='{$t[0]}'");
			$isViewBool = ( $isView === '1' );
			
			if ( !$isViewBool ) {
				$rr=$wo->db->query("select * from `". $t[0] . "`"); // get all the table's contents
				while($r=$wo->db->fetchAssoc($rr))
				{
					$row="insert into `". $t[0] ."` set"; // start the row description
					for($cntr=0;$cntr<$iterations;$cntr++) // add each column to the description
					{
						/*$clock++; // increment the query counter
							if ($clock==2000)
							{
							echo " "; // send a space so as to keep the browser alive and the messages popping up one by one
							$clock=0; // reset the query counter
							}*/
						$row.=" `". $columns[$cntr] ."`='". trim($wo->db->escape(str_replace("\\","",$r[$columns[$cntr]]))) ."',"; // first of all eliminate backslashes, then add one where needed then trim spaces
					}
					$row=substr($row,0,strlen($row)-1); // eliminate the ending comma
					echo  $row .";\r\n"; //add this row to the output
				}
			}
			//echo "</font><font color=green> OK !</font></font><br>\n";// next time write to the next line
		}
		
		$out = ob_get_contents();
		ob_end_clean();
		
		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$wo->db->getDataBaseName()."_BackUp.sql\";" );
		header("Content-Length: ". strlen($out));

		echo $out;
		
		exit();
	}	// backupDatabase

	//
	
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function getFullURL( $uri='', $publicSite=true ) {
		$finalPart = WOOOF::$instance->getConfigurationFor('siteBaseURL');

		if ( $publicSite ) {
			$finalPart .= WOOOF::$instance->getConfigurationFor('publicSite');
		}

		if ( $uri != '' ) {
			$finalPart .= $uri;
		}
		
		return	(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$finalPart";
	}	// getFullURL

	
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function returnJSON( $ok, $data, $errorMsg = '', $goOn = FALSE ) {
		$jsonString = json_encode( $data );
		if ( $jsonString === FALSE ) {
			echo json_encode(
					array(
							'ok' 		=> false,
							'errorMsg'	=> 'Failed to encode data!',
							'data'		=> $data // array()
					)
			);
		}
		else {
			echo json_encode(
					array(
							'ok' 		=> $ok,
							'errorMsg'	=> $errorMsg,
							'data'		=> $data
					)
			);
		}
	
		if ( !$goOn ) {
			die();
		}
	
		return true;
	} // returnJSON
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function returnJSON2( WOOOF $wo, $res ) {
	    if ( $res !== FALSE ) { self::returnJSON( true, $res, '' ); }
    	else { self::returnJSON( false, null, $wo->getErrorsAsStringAndClear() ); }
    	return true;
	} // returnJSON2
	
	
	
	/***************************************************************************/
	/***************************************************************************/

	/**
	 * 
	 * @param WOOOF $wo
	 * @param string $domain
	 * @param bool $isFullDomain
	 * @return false | string
	 */
	public static
	function formFullURL( WOOOF $wo, $domain, $isFullDomain = false ) {
		if ( !$wo->hasContent($domain) ) {
			$wo->logError(self::_ECP."0910 No value provided for [domain]");
			return false;
		}
		
		$domain = trim($domain);
		
		$dotParts = explode( '.', $domain );
		$noOfParts = count($dotParts); 
		$i = $noOfParts;
		while ( $i > 2  ) {
			$i--;
			array_shift($dotParts);
		}
		
		$domain = implode( '.', $dotParts);
				
		if ( substr($domain, 0, 4) === 'http' ) {
			$fullURL = $domain;
		}
		else {
			if ( !$isFullDomain and substr($domain, 0, 4) != 'www.' ) {
				$fullURL = 'www.' . $domain;
			}
			$fullURL = 'http://' . $domain;
		}
		
		return $fullURL;
		
	}

	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * Uses php's get_meta_tags function. Does not handle og: meta properties.
	 * @param WOOOF $wo
	 * @param string $domain		// just the 'ait.gr' part.
	 * @param bool $isFullDomain	// Default false.
	 * @return false | array[ 'meta name' => 'content', ... ]
	 */
	public static
	function getSiteMetadata( WOOOF $wo, $domain, $isFullDomain = false ) {
		$fullURL = self::formFullURL($wo, $domain, $isFullDomain );
		if ( $fullURL === false ) { return false; }
		
		$res = get_meta_tags($fullURL);
		if ( $res === FALSE ) {
			$wo->logError(self::_ECP."0920 Failed to get_meta_tags of [$domain].");
			return false;
		}
	
		return $res;
	
	}	// getSiteMetadata
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * Code taken from php manual page for get_meta_tags. Fetches the whole page!
	 * @param WOOOF $wo
	 * @param string $domain
	 * @param bool $isFullDomain
	 * @param bool $raw
	 * @return false | array [ title string, metaTags array, metaProperties ]
	 */
	public static 
	function getSiteMetadataAndProperties(
		WOOOF $wo, 
		$domain, 
		$isFullDomain = false, 
		$raw = false  // $raw - enable for raw display
	)
	{
		$fullURL = self::formFullURL($wo, $domain, $isFullDomain );
		if ( $fullURL === false ) { return false; }
		 
		$result = false;
		 
		$contents = self::getUrlContents($fullURL);
			
		if (isset($contents) && is_string($contents))
		{
			$title = null;
			$metaTags = null;
			$metaProperties = null;
			 
			preg_match('/<title>([^>]*)<\/title>/si', $contents, $match );
	
			if (isset($match) && is_array($match) && count($match) > 0)
			{
				$title = strip_tags($match[1]);
			}
			 
			preg_match_all('/<[\s]*meta[\s]*(name|property)="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $contents, $match);
			 
			if (isset($match) && is_array($match) && count($match) == 4)
			{
				$originals = $match[0];
				$names = $match[2];
				$values = $match[3];
				 
				if (count($originals) == count($names) && count($names) == count($values))
				{
					$metaTags = array();
					$metaProperties = $metaTags;
					if ($raw) {
						if (version_compare(PHP_VERSION, '5.4.0') == -1)
							$flags = ENT_COMPAT;
						else
							$flags = ENT_COMPAT | ENT_HTML401;
					}
					 
					for ($i=0, $limiti=count($names); $i < $limiti; $i++)
					{
						if ($match[1][$i] == 'name')
							$meta_type = 'metaTags';
						else
							$meta_type = 'metaProperties';
						if ($raw)
							${$meta_type}[$names[$i]] = array (
								'html' => htmlentities($originals[$i], $flags, 'UTF-8'),
								'value' => $values[$i]
							);
						else
							${$meta_type}[$names[$i]] = array (
								'html' => $originals[$i],
								'value' => $values[$i]
						);
					}
				}
			}

			foreach( $metaTags as $aKey => $aTag ) {
				$result[strtolower($aKey)] = $aTag['value']; 
			}
			foreach( $metaProperties as $aKey => $aProperty ) {
				$result[strtolower($aKey)] = $aProperty['value'];
			}
			$result['title'] = $title;
				
			/*
			$result = array (
				'title' => $title,
				'metaTags' => $metaTags,
				'metaProperties' => $metaProperties,
			);
			*/
			
		}
					 
		return $result;
	}	// getSiteMetadataAndProperties

	public static	
	function getUrlContents($url, $maximumRedirections = null, $currentRedirection = 0)
	{
		$result = false;
	 
		$contents = @file_get_contents($url);
  
		// Check if we need to go somewhere else
				 
		if (isset($contents) && is_string($contents))
		{
			preg_match_all('/<[\s]*meta[\s]*http-equiv="?REFRESH"?' . '[\s]*content="?[0-9]*;[\s]*URL[\s]*=[\s]*([^>"]*)"?' . '[\s]*[\/]?[\s]*>/si', $contents, $match);
			 
			if (isset($match) && is_array($match) && count($match) == 2 && count($match[1]) == 1)
			{
				if (!isset($maximumRedirections) || $currentRedirection < $maximumRedirections)
            	{
	            	return self::getUrlContents($match[1][0], $maximumRedirections, ++$currentRedirection);
				}
				 
				$result = false;
			}
			else
			{
				$result = $contents;
			}
		}
			 
		return $contents;
	}	// getUrlContents

	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * Uses php's get_meta_tags function. Does not handle og: meta properties.
	 * @param WOOOF $wo
	 * @param string $domain		// just the 'ait.gr' part.
	 * @param bool $isFullDomain	// Default false.
	 * @return false | array[ 'meta name' => 'value', ... ]
	 */
	public static
	function getSiteMetadataV3( WOOOF $wo, $domain, $isFullDomain = false ) {
		$fullURL = self::formFullURL($wo, $domain, $isFullDomain );
		if ( $fullURL === false ) { return false; }
	
		$metadata = [];
	  	$metadataIterator = SiteMetaData::fetch($domain);
	  	if ( $metadataIterator === FALSE ) { return false; }

		foreach ($metadataIterator as $key => $value) {
			$metadata[$key] = $value;
	  	}
	  	
	  	return $metadata;
			
	}	// getSiteMetadataV3
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * Copy fromArray to Array only specific key entries.
	 * Optionally remove prefix from the 'from' keys (e.g. VIEW column prefixes)
	 * @param Array $toArray
	 * @param String[] $keys
	 * @param Array $fromArray
	 * return array
	 */
	public static 
	function filterOnKeys (&$toArray, $keys, $fromArray, $removeKeyPrefix='') {
		if ( $removeKeyPrefix == '' ) {
			foreach ($keys as $key) {
				$toArray[$key] = $fromArray[$key];
			}
		}
		else {
			foreach ($keys as $key) {
				$toArray[str_replace($removeKeyPrefix, '', $key)] = $fromArray[$key];
			}
		}
		
	} //filterOnKeys
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * sqlListFromArray	Takes anarray of items and returns a string with the values quoted and possibly inserted inside parentheses 
	 * 
	 * @param Array $anArray 			An array of items to turn to list. The items should be ESCAPED beforehand.
	 * @param String $quoteCharacter	The character to use as a quote 
	 * @param String $spacer 			The spacer string to insert between the items
	 * @param Boolean $addParentheses 	Whether parentheses should be added or not
	 * @return String 	A quoted, in parentheses, sql list string for using in SQL queries.
	 */
	public static 
	function sqlListFromArray ($anArray, $quoteCharacter='\'', $spacer=', ', $addParentheses = TRUE) 
	{
		$retVal = implode($quoteCharacter.$spacer.$quoteCharacter, $anArray);
		$retVal =  $quoteCharacter. $retVal . $quoteCharacter ;
		if ($addParentheses)
		{
			$retVal = '('. $retVal .')';
		}
		return $retVal;
	} //sqlListFromArray
	
	
	
}	// WOOOF_Util