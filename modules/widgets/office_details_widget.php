

<p><strong><?= $question; ?></strong></p>

<ul>
	<?php foreach($items as $item) : ?>
		<li><?= $item; ?></li>
	<?php endforeach; ?>
</ul>

<p>Contact <a href="mailto:<?= $footer_text; ?>"><?= $footer_link_text; ?></a> <?= $footer_text; ?></p>
