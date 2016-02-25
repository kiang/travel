<div class="Comments form">
    <h3><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>留言</h3>
    <?php
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '這裡的評分可以讓下一位網友作為行程規劃的參考！'));
    echo '評分</div><div id="rankingAtComment"></div><div class="clear"></div><br />';
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '也許可以點出你希望強調的文字'));
    echo '標題</div>' . $this->Form->input('Comment.title', array(
        'label' => false,
        'div' => 'span-9 last',
        'class' => 'span-9',
    ));
    echo '<div class="clear"></div>';
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '留言的內容，這裡必須輸入文字'));
    echo '內容</div>' . $this->Form->input('Comment.body', array(
        'type' => 'textarea',
        'label' => false,
        'div' => 'span-9 last',
        'class' => 'span-9',
        'rows' => 2,
    ));
    echo '<div class="tooltip"></div>';
    echo '<div class="clear"></div>';
    ?>
</div>