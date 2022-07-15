<?php
/*

<p><strong><?= $question; ?></strong></p>

<ul>
	<?php foreach($items as $item) : ?>
		<li><?= $item; ?></li>
	<?php endforeach; ?>
</ul>

<p>Contact <a href="mailto:<?= $footer_text; ?>"><?= $footer_link_text; ?></a> <?= $footer_text; ?></p>

*/
?>


<?php

if(!empty(get_option('fdb_api_settings')[$widget_id_key])):
	$options = get_option( 'fdb_api_settings' );
	$widget_id = $options[$widget_id_key];
	$source_address = $options['fdb_source_widget'];
	if(!empty($widget_id) && !empty($source_address)):
		//print_r($options);
		$connected_url = $source_address.'/wp-json/wp/v2/fdcpt_widget/'.$widget_id;
	endif;


		//$connected_url = 'http://boiler.local/wp-json/wp/v2/fdcpt_widget/137';


	$response = wp_remote_get($connected_url);
	$api_response = json_decode( wp_remote_retrieve_body( $response ), true );

	//print_r($api_response['content']['rendered']);

endif;

?>

<?php if(!empty($api_response)) :?>
	<?=$api_response['content']['rendered'];?>
<?php endif; ?>
