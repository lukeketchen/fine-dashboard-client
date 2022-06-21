
<p style="color: green;font-size: 18px;"><strong><?= $question; ?></strong></p>

<ul>
	<?php foreach($items as $item) : ?>
		<li><a href="<?= $item['link_url'] ?>" target="_blank"><?= $item['link_text']; ?></a></li>
	<?php endforeach; ?>
</ul>

<p>Contact <a href="mailto:<?= $footer_text; ?>"><?= $footer_link_text; ?></a> <?= $footer_text; ?></p>
