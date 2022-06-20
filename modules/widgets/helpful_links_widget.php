

<?= $content[0]['question']; ?>

<ul>
	<?php foreach($content[1]['items'] as $item) : ?>
		<li><?= $item['content']; ?> <a href="<?= $item['link_address']; ?>"  target="_blank" rel="noopener noreferrer"><?= $item['link_text']; ?></a></li>
	<?php endforeach; ?>
</ul>
