<?php
if (!empty($items)) {
    $i = 0;
    foreach ($items AS $item) {
        ++$i;
        ?>
        <div class="span1">
            <a class="span1 category <?php echo $item['Activity']['class']; ?>" data-id="<?php echo $item['Activity']['id']; ?>">
                <span title="<?php echo $item['Activity']['name']; ?>"><?php echo $item['Activity']['name']; ?></span>
            </a>
            <div class="span1"><?php echo $item['Activity']['name']; ?></div>
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
                    var activityId = $(this).attr('data-id');
                    var targetObj = $('#' + activityTarget);
                    targetObj.val(activityId);
                    if($('#' + activityTarget + 'Icon').length > 0) {
                        $('#' + activityTarget + 'Icon').remove();
                    }
                    var newLinkObj = $(this).clone().attr('id', activityTarget + 'Icon');
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