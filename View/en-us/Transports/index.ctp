<?php
if (!empty($items)) {
    $i = 0;
    foreach ($items AS $item) {
        ++$i;
        ?>
        <div class="span1">
            <a href="#" class="span1 category <?php echo $item['Transport']['class']; ?>" data-id="<?php echo $item['Transport']['id']; ?>">
                <span title="<?php echo $item['Transport']['name']; ?>"><?php echo $item['Transport']['name']; ?></span>
            </a>
            <div class="span1"><?php echo $item['Transport']['name']; ?></div>
        </div>
        <?php
        if ($i % 9 == 0) {
            echo '<div class="clearfix"></div>';
        }
    }
    ?><script type="text/javascript">
            <!--
            $(function() {
                $('a.category').click(function() {
                    var transportId = $(this).attr('data-id');
                    var targetObj = $('#' + transportTarget);
                    targetObj.val(transportId);
                    if($('#' + transportTarget + 'Icon').length > 0) {
                        $('#' + transportTarget + 'Icon').remove();
                    }
                    var newLinkObj = $(this).clone().attr('id', transportTarget + 'Icon');
                    newLinkObj.click(function() {
                        return false;
                    });
                    targetObj.after(newLinkObj);
                    $('#dialogFull').dialog('close');
                    return false;
                });
            });
            -->
    </script><?php
}
?>