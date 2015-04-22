<?php

/**
 * Logger 0.0.1
 *
 * Logger is a single PHP class that can be used to easily write log files in
 * CSV format.
 *
 * @author     Kristijan Horvat Vuković <kris.vukovic@gmail.com>
 * @link       https://github.com/krivukov/logger
 * @license    N/A
 */

 class Logger {

	/**
	 * Name of the log file.
	 * @access private
	 */
	private $filename;

	/**
	 * Separator for the fields. Default is semicolon (;).
	 * @access private
	 */
	private $separator;

	/**
	 * Header line of the log file.
	 * @access private
	 */
	private $header;

	/**
	 * Constructor
	 * @param string $filename Path and name of the log file.
	 * @param string $separator Character used for separating the field values.
	 */
	function Logger($filename = "./log.csv", $separator = ";") {
		$this->filename = $filename;
		$this->separator = $separator;
		$this->header = implode($this->separator, array(
			"DATE_TIME",
			"ERROR_LEVEL",
			"MESSAGE",
			"FILE",
			"LINE"
		));
	}

	/**
	 * Method for writing messages to the log file.
	 * 
	 * @param string $level Custom error level (info, debug, etc...)
	 * @param string $message The message that will be written to the log file.
	 */
	function log($level = "INFO", $message = "") {
		$header = !file_exists($this->filename);
		$fh = @fopen($this->filename, "a");

		if ($fh) {
			if ($header) {
				fwrite($fh, $this->header."\n");
			}

			$datetime = date("Y-m-d H:i:s");
			$message = preg_replace("/\s+/", " ", trim($message));
			$backtrace = debug_backtrace();
			$file = $backtrace[0]['file'];
			$line = $backtrace[0]['line'];
		
			fputcsv($fh, array(
				$datetime,
				$level,
				$message,
				$file,
				$line
				),
				$this->separator
			);

			fclose($fh);
		}
	}

	/**
	 * Private method for debugging purposes.
	 */
	private function dump($var) {
		echo "<pre>".print_r($var, true)."</pre>";
		die;
	}
}
