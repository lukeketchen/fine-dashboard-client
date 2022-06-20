

<p style="color: green;font-size: 18px;"><strong><?= $content[0]['question']; ?></strong></p>

<ul>
	<?php foreach($content[1]['items'] as $item) : ?>
		<li><?= $item; ?></li>
	<?php endforeach; ?>
</ul>

<p>Contact <a href="mailto:<?= $content[2]['footer_text']; ?>"><?= $content[2]['footer_link_text']; ?></a> <?= $content[2]['footer_text']; ?></p>
