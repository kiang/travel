<div class="oauths view">
<h2><?php  echo __('Oauth'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($oauth['Oauth']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Member'); ?></dt>
		<dd>
			<?php echo $this->Html->link($oauth['Member']['username'], array('controller' => 'members', 'action' => 'view', $oauth['Member']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Provider'); ?></dt>
		<dd>
			<?php echo h($oauth['Oauth']['provider']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Uid'); ?></dt>
		<dd>
			<?php echo h($oauth['Oauth']['uid']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Oauth'), array('action' => 'edit', $oauth['Oauth']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Oauth'), array('action' => 'delete', $oauth['Oauth']['id']), null, __('Are you sure you want to delete # %s?', $oauth['Oauth']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Oauths'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Oauth'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Members'), array('controller' => 'members', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Member'), array('controller' => 'members', 'action' => 'add')); ?> </li>
	</ul>
</div>
