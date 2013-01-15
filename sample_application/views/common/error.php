<?php $this->load_view('common/header', array('title' => 'Uh oh, Something Broke!')); ?>


<p>
	There was a problem <?=$message?>.  The event has been logged and we'll take a look at it.
</p>

<?php $this->load_view('common/footer'); ?>