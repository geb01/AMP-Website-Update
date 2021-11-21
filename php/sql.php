<?php
	
	$database_cache = null;

	/**
	 * Connects to default database.
	 * @param boolean $mysql connect to mysql or sqlite 
	 * @return PDO
	 */
	function database($mysql = false) {
		global $database_cache;
		if (!is_null($database_cache)) return $database_cache;

		if (!$mysql) {
			$prefix = '';
			while (true) {
				$db_file = $prefix.'db/info.db';
				if (file_exists($db_file)) {
					$database_cache = new PDO('sqlite:'.$db_file);
					return $database_cache;
				} else {
					$prefix .= '../';
				}
			}
		} else {
			$user = 'root';
			$pass = 'root';
			$database_cache = new PDO('mysql:host=localhost;dbname=amp', $user, $pass);
			return $database_cache;
		}
	}

	/**
	 * Converts a variable to an SQL-friendly string. Enquotes strings, joins arrays, and stringifies nulls.
	 * @param type $variable 
	 * @return string
	 */
	function queryable($variable) {
		if ($variable === null) return 'NULL';
		if (is_array($variable)) return queryable(join('\n', $variable));
		if (is_string($variable)) return '"'.str_replace('"', '""', $variable).'"';
		return strval($variable);
	}

	/**
	 * A helper function for insert queries.
	 * @param PDO $database 
	 * @param array $tableName 
	 * @param array $data 
	 * @param array $columns 
	 * @return PDOStatement
	 */
	function queryInsert($database, $tableName, $data, $columns = null) {
		$query = 'INSERT INTO '.$tableName;
		if ($columns !== null)
			$query .= '('.join(',', $columns).') ';
		$query .= ' VALUES('.join(',', array_map('queryable', $data)).')';
		return $database->query($query);
	}

	/**
	 * Collects and transforms a query into an array.
	 * @param PDO $database 
	 * @param string $query 
	 * @return array
	 */
	function queryCollect($database, $query) {
		$data = array();
		foreach ($database->query($query) as $row) {
			$data[] = $row;
		}
		return $data;
	}

	/**
	 * Converts the query into a 2D array where the first row contains the column names.
	 * @param PDO $database 
	 * @param string $query 
	 * @return array
	 */
	function queryToArray($database, $query) {
		$result = $database->query($query);
		if (!$result) die('Server error');

		$columns = array();
		for ($i = 0; $i < $result->columnCount(); ++$i)
			$columns[] = $result->getColumnMeta($i)->$name;

		$table = array($columns);

		foreach ($result as $row) {
			$tableRow = array();
			foreach ($columns as $column) {
				$tableRow[] = $row[$column];
			}
			$table[] = $tableRow;
		}

		return $table;
	}

	/**
	 * Prints an html table describing the database query.
	 * @param type $database 
	 * @param type $query 
	 * @param string $mapper the mapper function to be applied to every cell
	 * @return void
	 */
	function queryToHtml($database, $query, $mapper = null) {
		$table = queryToArray($database, $query);
		echo '<table border=1>';
		foreach ($table as $row) {
			echo '<tr><td>';
			echo join('</td><td>', $mapper ? array_map($mapper, $row) : $row);
			echo '</td></tr>';
		}
		echo '</table>';
	}
?>