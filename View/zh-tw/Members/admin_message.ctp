<div class="Members form">
    <?php echo $this->Form->create('Member', array('url' => array('action' => 'message'))); ?>
    <h3>發送訊息給會員</h3>
    <div class="control-group">
        <div class="control-group input-prepend span10">
            <label class="add-on">標題</label>
            <?php
            echo $this->Form->input('subject', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span10',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span10">
            <label class="add-on">訊息</label>
            <?php
            echo $this->Form->input('message', array(
                'type' => 'textarea',
                'rows' => 10,
                'label' => false,
                'div' => false,
                'class' => 'span10',
            ));
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <input type="submit" class="offset1 btn btn-primary" value="送出" />
    <?php echo $this->Form->end(); ?>
</div>