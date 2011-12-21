<?php
/**
 * @package DF Draggable
 * @version 1.0
 */
/*
Plugin Name: DF Draggable 
Version: 1.0
Plugin URI: http://wordpress.org/extend/plugins/df-draggable/
Description: DF Draggable is a plugin for Wordpress that enables you to make elements draggable utilising jQuery UI draggable
Author: Dominic Fallows
Author URI: http://www.dominicfallows.com/apps-plugins/df-draggable/
License: GPLv2 or later
Tags: jquery, jquery ui, drag, drop, draggable

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

function currentURL() {
        
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") { $pageURL .= "s"; }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

?>
