<?php

$id = md5(serialize($url));
echo '<span id="' . $id . '">';
//icon icon_watch
if ($inFavorite) {
    /*
     * 已經在最愛中，預設顯示藍色心，滑鼠游標移過時改灰色，點選連結後移除
     */
    echo $this->Html->link('<i class="icon-eye-close"></i> 關注', array_merge($url, array('del')), array(
        'title' => '這個項目已經在最愛中，點選這裡就會把瀏覽中的項目從最愛移除',
        'class' => 'btn',
        'escape' => false,
    ));
} else {
    /*
     * 沒在最愛中，預設顯示灰色心，滑鼠游標移過時改藍色，點選連結後新增
     */
    echo $this->Html->link('<i class="icon-eye-open"></i> 關注', array_merge($url, array('add')), array(
        'title' => '這個項目已經在最愛中，點選這裡就會把瀏覽中的項目從最愛移除',
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