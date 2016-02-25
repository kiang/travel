<?php
if (!empty($linkControlMessage)) {
    echo $linkControlMessage;
} else {
    $url = array();
    if (!empty($foreignId) && !empty($foreignModel)) {
        $url = array('action' => 'add', $foreignModel, $foreignId);
    } else {
        $url = array('action' => 'add');
        $foreignModel = '';
    }
    echo $this->Form->create('Link', array('url' => $url));
    echo '<div id="LinksControlAddForm"></div>';
    echo $this->Form->end('送出');
    ?><hr />
    <script type="text/javascript">
        $(function() {
            $('#LinksControlAddForm').load('<?php echo $this->Html->url(array('action' => 'form', 0, $foreignModel)); ?>');
            var submitted = false;
            $('form#LinkAddForm').submit(function() {
                if(false === submitted) {
                    submitted = true;
                    $('#linkControlMessage').hide();
                    $.post('<?php echo $this->Html->url($url); ?>', $(this).serializeArray(), function(pageData) {
                        if(pageData == 'done') {
                            $('#relatedLinks').load('<?php echo $this->Html->url(array_merge($url, array('action' => 'index'))); ?>');
                        } else {
                            $('#linkControlMessage').html(pageData);
                            $('#linkControlMessage').show();
                        }
                    });
                }
                return false;
            });
        });
    </script><?php
}