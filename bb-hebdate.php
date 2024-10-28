<?php
/*
Plugin Name: BB's Hebrew Date
Description: BB's plugin for displaying the hebrew date on your site
Author: Baruch Burstein
Version: 1.0
*/

add_filter('date_i18n', 'bb_hebrew_date', 10, 4);
function bb_hebrew_date($datestr, $formatstring, $timestamp, $gmt)
{
	if(substr($datestr, 0, 5) == "\xFE\xFF\xEF\xEE\xED")
	{
		return substr($datestr, 5);
	}
	else
	{
		$heb = explode(' ', mb_convert_encoding(jdtojewish(unixtojd($timestamp), true), 'UTF-8', 'ISO-8859-8'));
		if(count($heb) == 4)
		{
			$heb[1] .= ' ' . $heb[2];
			$heb[2] = $heb[3];
		}
		$formatstring = str_replace(array('d', 'j', 'S'), array($heb[0], $heb[0], ''), $formatstring);
		$formatstring = str_replace(array('F', 'm', 'M', 'n'), $heb[1], $formatstring);
		$formatstring = str_replace(array('o', 'Y', 'y'), $heb[2], $formatstring);
		$formatstring = "\xFE\xFF\xEF\xEE\xED" . $formatstring;
		return date_i18n($formatstring, $timestamp, $gmt);
	}
}
?>