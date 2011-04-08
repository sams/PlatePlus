<h2><?php echo sprintf(__('Delete Page "%s"?', true), $page['Page']['title']); ?></h2>
<p>	
	<?php __('Be aware that your Page and all associated data will be deleted if you confirm!'); ?>
</p>
<?php
	echo $this->Form->create('Page', array(
		'url' => array(
			'action' => 'delete',
			$page['Page']['id'])));
	echo $form->input('confirm', array(
		'label' => __('Confirm', true),
		'type' => 'checkbox',
		'error' => __('You have to confirm.', true)));
	echo $form->submit(__('Continue', true));
	echo $form->end();
?>