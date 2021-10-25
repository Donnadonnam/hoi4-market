<?php

use \tuefekci\helpers;
use tuefekci\helpers\Files as Files;
use tuefekci\helpers\Arrays as Arrays;

/**
 * A class to parse LUA files to an PHP array.
 *
 * @version	1.0.3
 */
class LUAParser {

	/**
	 * Contains the lines of the LUA file.
	 */
	private $_lua;

	/**
	 * Contains the current position of the LUA array.
	 */
	private $_pos;

	/**
	 * Contains the nuber of array elements.
	 */
	private $_lines;

	/**
	 * Contains a list of keys that have to be present one or more times in the LUA file.
	 */
	private $_syntax_tokens = array();

	/**
	 * Contains the parsed LUA data from file.
	 */
	public $data;

	/**
	 * parseFile - Read and parse the given file.
	 *
	 * @param	string	$path	A valid LUA file path.
	 */
	public function parseFile($path) {

		// Check for file
		if(is_file($path) === false) {
			throw new Exception('Invalid input file (' . $path . ')');
		}

		// Initialise/reset vars
		$this->_lua = array();
		$this->data = array();
		$this->_pos = 0;
		$this->_lines = 0;

		// Read the file
		if(($lua = file_get_contents($path)) !== false) {

			$lua = strip_comments($lua);
			$lua = correctSyntax($lua);

			file_put_contents(__TMP__."/luaparser_filtered_raw.txt", $lua);


			// Check syntax if one or more keys has been added
			if($this->checkSyntax($lua) === true) {

				// Split by new line and count lines
				$this->_lua = preg_split('/\r\n|\r|\n/', $lua);
				$this->_lines = count($this->_lua);

				// Free resources
				unset($lua);

				// Very small array, something is wrong
				if($this->_lines < 2) {
					
					throw new Exception('Could not parse LUA file '. $this->_lines);
				}

				// Parse the LUA data
				$this->data = $this->parseLUA();

				// Free resources
				unset($this->_lua);
			}

			// One or more keys are missing in LUA file
			else {

				// Free resources
				unset($lua);

				// Throw an syntax error exception
				throw new Exception('Syntax error in lua file (' . $path . ')');
			}
		}

		// Could not read file
		else {
			throw new Exception('Could not read input file (' . $path . ')');
		}
	}

	/**
	 * checkSyntax - Checks if the list of defined tokens available in the LUA file.
	 *
	 * @param	ref string	$lua The content of the LUA file.
	 * @return	bool		Return True ist the syntax is ok or no tokens were specified, otherwise the function will return False.
	 */
	private function checkSyntax(&$lua) {

		// Check if some data available
		if(isset($this->_syntax_tokens[0]) === true) {
			foreach($this->_syntax_tokens as $token) {
				if(mb_strpos($lua, $token) === false) {
					return false;
				}
			}

			// Return true if we have no early return in case of missing token
			return true;
		}

		// No syntax checking, validate data
		else {
			return true;
		}
	}

	private function isAssoc(array $arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
	

	/**
	 * parseLUA - Parses the contents of the LUA file.
	 */
	private function &parseLUA() {

		// Initialise vars
		$data = array();
		$end = false;

		// The end of array has not been reached
		if($this->_pos < $this->_lines) {

			// Loop through LUA array
			while($end === false) {

				// End reached
				if($this->_pos >= $this->_lines) {
					break;
				}

				if(empty($this->_lua[$this->_pos])) {
					$this->_pos++;
					continue;
				}


				// Explode by assignment character
				$parts = explode('=', $this->_lua[$this->_pos]);

				// Trim values
				$parts[0] = trim($parts[0]);

				// Trim if part exists
				if(isset($parts[1]) === true) {
					$parts[1] = trim($parts[1]);
				}

				if(isset($parts[0]) === true && !empty($parts[0]) && ($parts[0][0] === '#')) {
					$this->_pos++;
					continue;
				}


				if(isset($parts[1])  && !isset($parts[1][0])) {
					var_dump($parts);
				}


				// Start of table
				if(isset($parts[1]) === true && ($parts[1] === '{' || (empty($parts[1]) === true && $parts[1] != 0))) {

					// When Bracket is in next line, skip the next line
					$this->_pos += (empty($parts[1]) === true) ? 2 : 1;

					// Parse content

					$key = $this->getValue($parts[0], true);
					$nextData = $this->parseLUA();
					$newData = array();

					if(!empty($data[$key]) && is_array($data[$key])) {

						if($this->isAssoc($data[$key])) {
							$newData[] = $data[$key];
						} else {
							$newData = $data[$key];
						}

						$newData[] = $nextData;
					} elseif(!empty($data[$key])) {
						$newData = array();
						$newData[] = $data[$key];
						$newData[] = $nextData;
					} else {
						$newData = $nextData;
					}


					$data[$key] = $newData;

				} else if($parts[0] === '}' || $parts[0] === '},') { // End of table

					$end = true;
					$this->_pos++;

				} else if(isset($parts[1]) == true && $parts[1][0] == '{' &&  mb_strlen($parts[1]) > 1 && ($subpart = trim(substr($parts[1], 1))) && ($subpart == '},' || $subpart == '}')) { // { }, case

					if(empty($data[$this->getValue($parts[0], true)])) {
						$data[$this->getValue($parts[0], true)] = array();
					}
					$this->_pos++;

				}

				// Get value
				else {
					// Data has been found
					if (!empty($parts[1])) {
						// There's a key, so save key to avoid multiply function execution
						$key = $this->getValue($parts[0], true);

						if(mb_strlen($key) > 0 && mb_strlen($parts[1]) > 0) {

							$nextData = $this->getValue($parts[1], false);
							$newData = array();

							if(!empty($data[$key]) && is_array($data[$key])) {
		
								if($this->isAssoc($data[$key])) {
									$newData[] = $data[$key];
								} else {
									$newData = $data[$key];
								}
		
								$newData[] = $nextData;
							} elseif(!empty($data[$key])) {
								$newData = array();
								$newData[] = $data[$key];
								$newData[] = $nextData;
							} else {
								$newData = $nextData;
							}
		
							$data[$key] = $newData;
						}
					} else {
						// There isn't a key, so just add to the end of the array
						if (mb_strlen($parts[0]) > 0) {
							$data[] = $this->getValue($parts[0], false);
						}
					}

					// Increase position
					$this->_pos++;
				}
			}
		}

		// Return fetched data by reference
		return $data;
	}

	/**
	 * getValue - Removes control characters from the given string.
	 *
	 * @param	string	$str	A string.
	 * @return	mixed	Return the string without control characters.
	 */
	private function &getValue(&$str, $is_id) {

		// Remove spaces at start and end
		$str = trim($str);

		// Remove controls characters from ID
		if($is_id === true) {
			$str = str_replace(array('"', '[', ']'), '', $str);
		}

		// Remove control characters from value
		else {

			// Remove ending control characters
			if(mb_substr($str, -2) === '",') {
				$str = mb_substr($str, 0, -2);
			}
			else if(mb_substr($str, -1) === '"' || mb_substr($str, -1) === ',') {
				$str = mb_substr($str, 0, -1);
			}

			// Remove starting control character
			if(mb_substr($str, 0, 1) === '"') {
				$str = mb_substr($str, 1);
			}
		}

		// Remove spaces at start and end
		$str = trim($str);

		// Return fetched data by reference
		return $str;
	}
}

 function strip_comments($source) {
	return $text = preg_replace('/#.*/','',preg_replace('#//.*#','',preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#','',($source))));
 }

function correctSyntax(string $text) {
	$code = explode(PHP_EOL, $text);

	foreach($code as $line => $value) {

		$value = trim($value);

		if($value === "{") {
			$neighbors = Arrays::neighborKeys($code, $line);

			$code[$neighbors['prev']] = $code[$neighbors['prev']]." {";
			unset($code[$line]);
		}

	}
 
	return implode(PHP_EOL, $code);
}


?>