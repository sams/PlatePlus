<div class="pages index">
<h2><?php __('Pages');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>

<?php
	echo $this->Form->create('Page', array(
		'url' => array_merge(array('action' => 'find'), $this->params['pass'])
		));
	//echo $this->Form->input('title', array('div' => false));
	echo $this->Form->submit(__('Search', true), array('div' => false));
	echo $this->Form->end();
?>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('parent_id');?></th>
	<th><?php echo $this->Paginator->sort('lft');?></th>
	<th><?php echo $this->Paginator->sort('rght');?></th>
	<th><?php echo $this->Paginator->sort('slug');?></th>
	<th><?php echo $this->Paginator->sort('url');?></th>
	<th><?php echo $this->Paginator->sort('title');?></th>
	<th><?php echo $this->Paginator->sort('content');?></th>
	<th><?php echo $this->Paginator->sort('description_meta_tag');?></th>
	<th><?php echo $this->Paginator->sort('published');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th><?php echo $this->Paginator->sort('updated');?></th>
	<th><?php echo $this->Paginator->sort('user_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($pages as $page):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $page['Page']['id']; ?>
		</td>
		<td>
			<?php echo $this->Html->link($page['ParentPage']['title'], array('controller' => 'pages', 'action' => 'view', $page['ParentPage']['id'])); ?>
		</td>
		<td>
			<?php echo $page['Page']['lft']; ?>
		</td>
		<td>
			<?php echo $page['Page']['rght']; ?>
		</td>
		<td>
			<?php echo $page['Page']['slug']; ?>
		</td>
		<td>
			<?php echo $page['Page']['url']; ?>
		</td>
		<td>
			<?php echo $page['Page']['title']; ?>
		</td>
		<td>
			<?php echo $page['Page']['content']; ?>
		</td>
		<td>
			<?php echo $page['Page']['description_meta_tag']; ?>
		</td>
		<td>
			<?php echo $page['Page']['published']; ?>
		</td>
		<td>
			<?php echo $page['Page']['created']; ?>
		</td>
		<td>
			<?php echo $page['Page']['updated']; ?>
		</td>
		<td>
			<?php echo $this->Html->link($page['User']['id'], array('controller' => 'users', 'action' => 'view', $page['User']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $page['Page']['slug'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $page['Page']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $page['Page']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php echo $this->element('paging',array('plugin'=>'templates')); ?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('New Page', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Pages', true), array('controller' => 'pages', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Page', true), array('controller' => 'pages', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
