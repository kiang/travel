<?php
echo $this->Form->create('Submit', array('url' => array('action' => 'edit', $id)));
foreach ($data AS $model => $fields) {
    foreach ($fields AS $field => $value) {
        $key = $model . '.' . $field;
        if (is_array($value)) {
            foreach ($value AS $k2 => $v2) {
                $key2 = $key . '.' . $k2;
                ?><div class="control-group input-prepend span5">
                    <label class="add-on"><?php echo $key2; ?></label>
                    <?php
                    echo $this->Form->input($key2, array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span4',
                        'value' => $v2,
                    ));
                    ?>
                </div>
                <?php
            }
        } else {
            ?><div class="control-group input-prepend span5">
                <label class="add-on"><?php echo $key; ?></label>
                <?php
                echo $this->Form->input($key, array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span4',
                    'value' => $value,
                ));
                ?>
            </div>
            <?php
        }
    }
}
?><div class="clearfix"></div><?php
echo $this->Form->end('送出');