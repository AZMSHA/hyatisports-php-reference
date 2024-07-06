<?php

$widgets = array(
				//'txt', 'rte', 'img', 'line'
				"1" => array( array('Link', 'line'), array('Image', 'img') ),
				"2" => array( array('Image', 'img') ),
				"3" => array( array('Designation', 'line'), array('Image', 'img') ),
				"4" => array( array('Link', 'line'), array('Image', 'img') )
			);


function has_widget_setting( $id ){
	global $widgets;
	return ( array_key_exists($id, $widgets) ) ? true : false;
}
