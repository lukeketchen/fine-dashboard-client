
<!-- <h2><?php //echo $content[0]['content']; ?></h2> -->




<?php



$options = get_option( 'fdb_api_settings' )['fdb_alert_widget_api'];
if(!empty($options)):
	//print_r($options);
	$connected_url = $options;
endif;


	//$connected_url = 'http://boiler.local/wp-json/wp/v2/fdcpt_widget/137';

?>

<?php
$response = wp_remote_get($connected_url);
$api_response = json_decode( wp_remote_retrieve_body( $response ), true );

//print_r($api_response['content']['rendered']);


?>

<?php if(!empty($api_response)) :?>
	<?=$api_response['content']['rendered'];?>
<?php endif; ?>
