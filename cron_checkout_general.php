<?php
/**
 * A script you can call to check everyone out.
 * Recommended to be once a day.
 * To do the outcheckings for example at four in the morning, add
 *
 * 		"0 4 * * * <path_to_php_executable> <path_to_this_script> >/dev/null 2>&1"
 *
 * to your crontab.
 */

require_once('db.class.php');

DB::getDb()->safeQuery(
		"UPDATE users
		SET checked_in_at = NULL"
		);
