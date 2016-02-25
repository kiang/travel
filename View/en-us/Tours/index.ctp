<?php
if ($loginMember['id'] > 0) {
    $this->Html->script('jquery.rating.pack', array('inline' => false));
}
?>
<div id="TabBox">
    <div id="tourIndexTab">
        <ul>
            <li><?php
echo $this->Html->link('Tours', '/tours/area/0', array('title' => 'new tours'));
?></li>
            <?php if (!empty($loginMember['id'])) { ?>
                <li><?php
            echo $this->Html->link('Share', '/tours/add', array('title' => 'share a tour'));
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