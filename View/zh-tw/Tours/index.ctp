<?php
if ($loginMember['id'] > 0) {
    $this->Html->script('jquery.rating.pack', array('inline' => false));
}
?>
<div id="TabBox">
    <div id="tourIndexTab">
        <ul>
            <li><?php
echo $this->Html->link('旅行社', '/tours/area/0', array('title' => 'new tour'));
?></li>
            <?php if (!empty($loginMember['id'])) { ?>
                <li><?php
            echo $this->Html->link('分享旅行社', '/tours/add', array('title' => 'share a tour'));
                ?></li>
            <?php } ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    <!--
    $(function() {
        $('div#tourIndexTab').tabs({
            cache: true,
            create: function() {
                $('ul', this).wrap($('<div class="block">'));
            }
        });
    });
    // -->
</script>