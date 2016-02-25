<?php
if ($loginMember['id'] > 0) {
    $this->Html->script('jquery.rating.pack', array('inline' => false));
}
?>
<div id="TabBox">
    <div id="pointIndexTab">
        <ul>
            <li><?php
echo $this->Html->link('最新回應', '/points/block_comment', array('title' => 'new comments'));
?></li>
            <li><?php
                echo $this->Html->link('熱門地點', '/points/block_hot', array('title' => 'hot points'));
?></li>
            <li><?php
                echo $this->Html->link('分享地點', '/points/add', array('title' => 'share a point'));
?></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    <!--
    $(function() {
        $('div#pointIndexTab').tabs({
            cache: true,
            create: function() {
                $('ul', this).wrap($('<div class="block">'));
            }
        });
    });
    // -->
</script>