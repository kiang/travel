<?php
echo $this->Form->create('Point', array('url' => array(
        'controller' => 'findings', 'action' => 'new_point', $findingId
        )));
echo '<div id="newPointForm"></div>';
echo $this->Form->end('Submit');
?>
<script type="text/javascript">
    $(function() {
        $('#newPointForm').load('<?php echo $this->Html->url(array('controller' => 'points', 'action' => 'form')); ?>');
    });
</script>