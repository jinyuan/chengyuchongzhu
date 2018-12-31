<?php

function write($data, $parent_tag, &$format, $error) {
	
	if($format == 'xml') {
		header('Content-type: text/xml');
    	echo '<' . $parent_tag . '>';
    	foreach($data as $index => $post) {
      		if(is_array($post)) {
        		foreach($post as $key => $value) {
          			echo '<',$key,'>';
          			if(is_array($value)) {
            			foreach($value as $tag => $val) {
              				echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
            			}
          			}
          			echo '</',$key,'>';
        		}
      		} else { foreach($data as $index => $post) { echo '<' . $index . '>' . $post . '</' . $index . '>'; } }
    	}
    	echo '</' . $parent_tag . '>';
	} else {
		header('Content-type: application/json; charset=UTF-8') ;
    	echo json_encode(array($parent_tag=>$data));
	}
	
	if(!empty($error)) { echo ' | error: ' .  $error; }
}

?>