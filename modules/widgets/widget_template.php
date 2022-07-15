<?php

if(!empty(get_option('fdb_api_settings')[$widget_id_key])):
	$options = get_option( 'fdb_api_settings' );
	$widget_id = $options[$widget_id_key];
	$source_address = $options['fdb_source_widget'];
	if(!empty($widget_id) && !empty($source_address)):
		$connected_url = $source_address.'/wp-json/wp/v2/fdcpt_widget/'.$widget_id;
	endif;
	$response = wp_remote_get($connected_url);
	$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
endif;


if(!empty($api_response)) :
	echo $api_response['content']['rendered'];
endif;
?>
