<div class="oauths form">
<?php echo $this->Form->create('Oauth'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Oauth'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('member_id');
		echo $this->Form->input('provider');
		echo $this->Form->input('uid');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Oauth.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Oauth.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Oauths'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Members'), array('controller' => 'members', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Member'), array('controller' => 'members', 'action' => 'add')); ?> </li>
	</ul>
</div>
