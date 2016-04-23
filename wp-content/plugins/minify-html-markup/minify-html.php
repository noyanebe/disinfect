<?php
/*
Plugin Name: Minify HTML
Plugin URI: https://wordpress.org/plugins/minify-html-markup/
Description: Minify your HTML for faster downloading and cleaning up sloppy looking markup.
Version: 1.6
Author: Tim Eckel
Author URI: https://www.dogblocker.com
License: GPLv3 or later
*/

/*
	Copyright 2016  Tim Eckel  (email : tim@leethost.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( !defined( 'ABSPATH' ) ) exit;

if ( !is_admin() ) add_action( 'init', 'teckel_init_minify_html', 1 );

function teckel_init_minify_html() {
	ob_start('teckel_minify_html_output');
}

function teckel_minify_html_output($buffer) {
	$buffer = str_replace(array (chr(13) . chr(10), chr(9)), array (chr(10), ''), $buffer);
	$buffer = str_ireplace(array ('<script', '/script>', '<pre', '/pre>', '<textarea', '/textarea>', '<style', '/style>'), array ('M1N1FY-ST4RT<script', '/script>M1N1FY-3ND', 'M1N1FY-ST4RT<pre', '/pre>M1N1FY-3ND', 'M1N1FY-ST4RT<textarea', '/textarea>M1N1FY-3ND', 'M1N1FY-ST4RT<style', '/style>M1N1FY-3ND'), $buffer);
	$split = explode('M1N1FY-3ND', $buffer);
	$buffer = '';
	for ($i=0; $i<count($split); $i++) {
		$ii = strpos($split[$i], 'M1N1FY-ST4RT');
		if ($ii !== false) {
			$process = substr($split[$i], 0, $ii);
			$asis = substr($split[$i], $ii + 12);
			if (substr($asis, 0, 7) == '<script') {
				$split2 = explode(chr(10), $asis);
				$asis = '';
				for ($iii = 0; $iii < count($split2); $iii ++) {
					if ($split2[$iii]) $asis .= trim($split2[$iii]) . chr(10);
				}
				if ($asis) $asis = substr($asis, 0, -1);
				$asis = str_replace(array (';' . chr(10), '>' . chr(10), '{' . chr(10), '}' . chr(10), ',' . chr(10)), array(';', '>', '{', '}', ','), $asis);
			}
			if (substr($asis, 0, 6) == '<style') {
				$asis = preg_replace(array ('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'), array('>', '<', '\\1'), $asis);
				$asis = str_replace(array (chr(10), ' {', '{ ', ' }', '} ', ' (', '( ', ' )', ') ', ' :', ': ', ' ;', '; ', ' ,', ', ', ';}'), array('', '{', '{', '}', '}', '(', '(', ')', ')', ':', ':', ';', ';', ',', ',', '}'), $asis);
			}
		} else {
			$process = $split[$i];
			$asis = '';
		}
		$process = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $process);
		$process = preg_replace(array ('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'), array('>', '<', '\\1'), $process);
		$buffer .= $process.$asis;
	}
	$buffer = str_replace(array (chr(10) . '<script', chr(10) . '<style', '*/' . chr(10), 'M1N1FY-ST4RT'), array('<script', '<style', '*/', ''), $buffer);
	return ($buffer);
}
?>