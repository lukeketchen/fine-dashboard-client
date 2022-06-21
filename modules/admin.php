<?php

?>

<div id="wpbody" role="main">

<div id="wpbody-content">
		<div id="screen-meta" class="metabox-prefs">

			<div id="contextual-help-wrap" class="hidden no-sidebar" tabindex="-1" aria-label="Contextual Help Tab">
				<div id="contextual-help-back"></div>
				<div id="contextual-help-columns">
					<div class="contextual-help-tabs">
						<ul>
												</ul>
					</div>


					<div class="contextual-help-tabs-wrap">
											</div>
				</div>
			</div>
				</div>

<div class="wrap wpsisacm-wrap">
	<style type="text/css">
		.wpos-box{box-shadow: 0 5px 30px 0 rgba(214,215,216,.57);background: #fff; padding-bottom:10px; position:relative;}
		.wpos-box ul{padding: 15px;}
		.wpos-box h5{background:#555; color:#fff; padding:15px; text-align:center;}
		.wpos-box h4{ padding:0 15px; margin:5px 0; font-size:18px;}
		.wpos-box .button{margin:0px 15px 15px 15px; text-align:center; padding:7px 15px; font-size:15px;display:inline-block;}
		.wpos-box .wpos-list{list-style:square; margin:10px 0 0 20px;}
		.wpos-clearfix:before, .wpos-clearfix:after{content: "";display: table;}
		.wpos-clearfix::after{clear: both;}
		.wpos-clearfix{clear: both;}
		.wpos-col{width: 47%; float: left; margin-right:10px; margin-bottom:10px;}
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpsisacm-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpsisacm-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
		.button-blue{background: #0055fb !important;border-color: #0055fb !important; font-weight: 600;}
	</style>
	<h2>Fine Dashboard</h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">

			<div id="post-body-content">

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span>How It Works</span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label>Getting Started with Fine Dashboard:</label>
										</th>
										<td>
//Primary Button
<input class='button-primary' type='submit' name='Save' value="<?php _e('Save Options'); ?>" id="submitbutton" />
<br>
// Secondary Button
<input type='submit' value='<?php _e('Search Attendees'); ?>' class='button-secondary' />
<br>
//Link Button
<a class="button-secondary" href="#" title="All Attendees">All Attendees</a>
<br>


<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" style="background-color: lightgrey;">
    <ul>
        <li><label for="fname">Family Name (Sir Name)<span> *</span>: </label>
        <input id="fname" maxlength="45" size="10" name="fname" value="" /></li>

        <li><label for="lname">Last Name<span> *</span>: </label>
        <input id="lname" maxlength="45" size="10" name="lname" value="" /></li>
    </ul>
</form>





<?php
	if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
        update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) );
    endif;
?>

<form method='post'>
	<div class='image-preview-wrapper'>
		<img id='image-preview' class="<?= get_option( 'media_selector_attachment_id' ); ?>" src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>' width='200'>
	</div>
	<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Select image' ); ?>" />
	<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
	<input type="submit" name="submit_image_selector" value="Save" class="button-primary">
</form>
<?php $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 ); ?>
<div id="attachment_id" data-id="<?= $my_saved_attachment_post_id; ?>"></div>



											<ul>
												<li>Step-1. Go to "".</li>
												<li>Step-2.</li>
												<li>Step-3. </li>
												<li>Step-4. </li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label>Content:</label>
										</th>
										<td>
											<ul>
												<li>Step-1. .</li>
												<li>Step-2. </li>
											</ul>
										</td>
									</tr>


									<tr>
										<th>
											<label>Documentation:</label>
										</th>
										<td>
											<a class="button button-primary" href="https://github.com/lukeketchen/fine-dashboard" target="_blank">Check Documentation</a>
										</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div><!-- .post-body-content -->


		</div>
	</div>
</div><!-- end .wpsisacm-wrap -->
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div>
