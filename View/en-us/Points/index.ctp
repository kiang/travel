<?php
if ($loginMember['id'] > 0) {
    $this->Html->script('jquery.rating.pack', array('inline' => false));
}
?>
<div id="TabBox">
    <div id="pointIndexTab">
        <ul>
            <li><?php
echo $this->Html->link('Latest', '/points/block_comment', array('title' => 'new comments'));
?></li>
            <li><?php
                echo $this->Html->link('Hot', '/points/block_hot', array('title' => 'hot points'));
?></li>
            <?php if (!empty($loginMember['id'])) { ?>
                <li><?php
            echo $this->Html->link('Share', '/points/add', array('title' => 'share a point'));
                ?></li>
            <?php } ?>
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