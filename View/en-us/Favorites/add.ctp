<?php

$id = md5(serialize($url));
echo '<span id="' . $id . '">';
//icon icon_watch
if ($inFavorite) {
    echo $this->Html->link('<i class="icon-eye-close"></i> Favorite', array_merge($url, array('del')), array(
        'title' => 'This is in your favorites, click here to remove it.',
        'class' => 'btn',
        'escape' => false,
    ));
} else {
    echo $this->Html->link('<i class="icon-eye-open"></i> Favorite', array_merge($url, array('add')), array(
        'title' => 'This is not in your favorites, click here to add it.',
        'class' => 'btn',
        'escape' => false,
    ));
}
echo $this->Html->scriptBlock('
$(function() {
	$(\'#' . $id . ' a\').click(function() {
		$(\'#' . $id . '\').load(this.href);
		return false;
	});
});
');
echo '</span>';