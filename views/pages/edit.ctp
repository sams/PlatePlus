<div class="pages form">
<?php echo $this->Form->create('Page', array('url' => array('action' => 'edit')));?>
	<fieldset>
 		<legend><?php __('Edit Page');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('parent_id');
		echo $this->Form->input('lft');
		echo $this->Form->input('rght');
		echo $this->Form->input('slug');
		echo $this->Form->input('url');
		echo $this->Form->input('title');
		echo $this->Form->input('content');
		echo $this->Form->input('description_meta_tag');
		echo $this->Form->input('published');
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Page.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Pages', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Pages', true), array('controller' => 'pages', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Page', true), array('controller' => 'pages', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>