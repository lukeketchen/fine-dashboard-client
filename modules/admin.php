<?php
/**
 * Renders the content of the admin page.
 *
 * @since    1.0.0
 *
 */
?>

<div class="wrap">

	<h1>Fine Dashboard - Client</h1>
	<p class="description">
		Set the api endpoint to get the data for the widgets.
	</p>

	<form action="" method="post">

		<!-- The set of option elements, such as checkboxes, would go here -->
		<label for="widgetalert">Alert Widget api</label>
		<input type="text" name="fdb-widgetalert" id="widgetalert" value="<?= get_option( 'fdb-widgetalert' ); ?>">
		<br>
		<label for="widgetone">Widget One api</label>
		<input type="text" name="fdb-widgetone" id="widgetone">
		<br>
		<label for="widgettwo">Widget Two api</label>
		<input type="text" name="fdb-widgettwo" id="widgettwo">
		<br>
		<label for="widgetthree">Widget Three api</label>
		<input type="text" name="fdb-widgetthree" id="widgetthree">
		<br>

		<?php submit_button( 'Save' ); ?>
		<?php wp_nonce_field( 'fdb-submenu-page-save', 'fdb-submenu-page-save-nonce' ); ?>


		<p>output test</p>
		<?= get_option( 'fdb-widgetalert' ); ?>

	</form>

</div><!-- .wrap -->
