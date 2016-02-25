<div class="groups index">
    <h2>設定權限</h2>
    <p>
        <?php
        $urlArray = array('url' => array($groupId));
        echo $this->Paginator->counter(array('format' => __('Page %page% / %pages% Pages')));
        ?>
    </p>
    <table class="table table-bordered" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('alias', 'Controller', $urlArray); ?></th>
            </tr>
        </thead>
        <?php
        $i = 0;
        foreach ($acos as $aco):
            $class = null;
            if ($i++ % 2 == 1) {
                $class = ' class="even"';
            }
            ?>
            <tr<?php echo $class; ?>>
                <td style="text-align:left;"><?php
        echo $aco['Aco']['alias'];
        if (!empty($aco['Aco']['Aco'])) {
            echo '<input type="checkbox" name="ctrl' . $aco['Aco']['alias'] . '" class="acoController">';
            echo '<hr /><div id="sub' . $aco['Aco']['alias'] . '">';
            foreach ($aco['Aco']['Aco'] AS $actionAco) {
                echo '<input type="checkbox" name="' . $aco['Aco']['alias'] . '___' . $actionAco['alias'] . '"';
                if ($actionAco['permitted'] == 1) {
                    echo ' checked="checked"';
                }
                echo ' class="acoPermitted">';
                echo $actionAco['alias'] . '&nbsp;';
            }
            echo '</div>';
        }
            ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php
echo $this->Form->create('Group', array('url' => array('action' => 'acos', $groupId)));
echo '<ul id="permissionStack"></ul>';
echo $this->Form->end('更新');
?>
<script type="text/javascript">
    $(function() {
        $('input.acoPermitted').click(function() {
            if($('#p' + this.name).size() > 0) {
                $('#p' + this.name).remove();
            } else {
                var itemValue = '+';
                if(!this.checked) {
                    itemValue = '-';
                }
                $('#permissionStack').append('<li id="p' + this.name + '">' +
                    itemValue + this.name.replace('___', '/') +
                    '<input type="hidden" name="' + this.name + '" value="' + itemValue + '">'+
                    '</li>');
            }
        });
        $('.acoController').click(function() {
            var controllerChecked = this.checked;
            $('div#' + this.name.replace('ctrl', 'sub') + ' input.acoPermitted').each(function() {
                if(this.checked != controllerChecked) {
                    this.click();
                }
            });
        });
    });
</script>
<div class="paging">
    <?php echo $this->Paginator->prev('<< ' . __('previous'), $urlArray, null, array('class' => 'disabled')); ?>
    | 	<?php echo $this->Paginator->numbers($urlArray); ?>
    <?php echo $this->Paginator->next(__('next') . ' >>', $urlArray, null, array('class' => 'disabled')); ?>
</div>
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link(__('List'), array('action' => 'index')); ?></li>
    </ul>
</div>