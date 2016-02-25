<div class="Transports form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo 'Edit';
} else {
    echo '新增';
}
?>交通方式</legend>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">名稱</label>
                <?php
                echo $this->Form->input('Transport.name', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span4">
                <label class="add-on">類別</label>
                <?php
                echo $this->Form->input('Transport.class', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-group input-prepend span8">
                <label class="add-on">介紹</label>
                <?php
                echo $this->Form->input('Transport.description', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span7',
                ));
                ?>
            </div>
        </div>
        <?php
        if ($id > 0) {
            echo $this->Form->input('Transport.id');
        }
        ?>
    </fieldset>
</div>