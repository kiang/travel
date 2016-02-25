<?php
echo $this->Form->create('Point', array('url' => array('action' => 'kml_import'), 'type' => 'file'));
echo $this->Form->file('kml');

if (!empty($pointStack)) {
    $idx = 0;
    foreach ($pointStack AS $point) {
        $prefix = 'Point.' . $idx . '.';
        ?><div class="clear">
            <div class="control-group input-prepend input-append span7">
                <?php echo $this->Form->checkbox($prefix . 'import', array('value' => 1, 'checked' => 'checked')); ?>
                <label class="add-on">名稱</label>
                <?php
                echo $this->Form->input($prefix . 'title', array(
                    'value' => $point['title'],
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                echo $this->Form->hidden($prefix . 'latitude', array('value' => $point['latitude']));
                echo $this->Form->hidden($prefix . 'longitude', array('value' => $point['longitude']));
                ?>
                <label class="add-on">
                    <?php echo $point['latitude'] . ', ' . $point['longitude']; ?>
                </label>
            </div>
        </div><?php
        ++$idx;
    }
}

echo $this->Form->end('送出');