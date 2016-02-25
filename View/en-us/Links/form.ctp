<div class="Links form">
    <h3><?php
if ($id > 0) {
    echo 'Edit';
} else {
    echo '新增';
}
?>連結</h3>
    <?php
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => 'Full Url, like http://travel.olc.tw'));
    echo 'Url</div>' . $this->Form->input('Link.url', array(
        'label' => false,
        'div' => 'span-8',
        'class' => 'span-8',
    ));
    echo '<div class="clear"></div>';
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '連結所顯示的名稱，例如輸入 \'就愛玩\' ，資料就會以這段文字當作連結，點選後連結到上面輸入的Url'));
    echo 'Title</div>' . $this->Form->input('Link.title', array(
        'label' => false,
        'div' => 'span-8',
        'class' => 'span-8',
    ));
    echo '<div class="clear"></div>';
    ?>
</div>