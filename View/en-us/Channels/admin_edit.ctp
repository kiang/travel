<div class="channels form">
    <?php echo $this->Form->create('Channel'); ?>
    <fieldset>
        <legend>編輯</legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('member_id', array(
            'type' => 'text',
            'class' => 'span3',
        ));
        echo $this->Form->input('url', array(
            'type' => 'text',
            'class' => 'span6',
        ));
        echo $this->Form->input('title', array(
            'type' => 'text',
            'class' => 'span6',
        ));
        echo $this->Form->input('summary', array(
            'type' => 'textarea',
            'class' => 'span6',
        ));
        echo $this->Form->input('the_date', array(
            'type' => 'text',
            'class' => 'span3',
        ));
        echo '<ul>';
        foreach ($this->request->data['ChannelLink'] AS $link) {
            echo '<li>';
            echo $this->Form->input('ChannelLink.model.' . $link['id'], array(
                'type' => 'text',
                'class' => 'span1',
                'div' => false,
                'label' => false,
                'value' => $link['model'],
            ));
            echo $this->Form->input('ChannelLink.foreign_key.' . $link['id'], array(
                'type' => 'text',
                'class' => 'span1',
                'div' => false,
                'label' => false,
                'value' => $link['foreign_key'],
            ));
            echo $this->Form->input('ChannelLink.foreign_title.' . $link['id'], array(
                'type' => 'text',
                'class' => 'span6',
                'div' => false,
                'label' => false,
                'value' => $link['foreign_title'],
            ));
            echo '</li>';
        }
        echo '</ul>';
        ?>
    </fieldset>
    <?php echo $this->Form->end('送出'); ?>
</div>