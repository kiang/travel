<div class="Areas form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>區域</legend>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">名稱</label>
                <?php
                echo $this->Form->input('Area.name', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                ?>
            </div>
            <?php if ($id > 0) { ?>
                <div class="control-group input-prepend span4">
                    <label class="add-on">類別</label>
                    <?php
                    echo $this->Form->input('Area.id');
                    echo $this->Form->input('Area.parent_id', array(
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            <?php } ?>
        </div>
    </fieldset>
</div>