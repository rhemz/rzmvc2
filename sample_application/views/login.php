<?php $this->load_view('common/header', array('title' => 'Login to Simple To-do!', 'alt' => 'Login')); ?>

<form action="" method="post">

	<label for="email"><?=$val->error('email') ? $val->message('email') : 'Email Address'?></label>
	<input type="text" id="email" name="email" value="<?=$val->value('email')?>" />

	<label for="password"><?=$val->error('password') ? $val->message('password') : 'Password'?></label>
	<input type="password" id="password" name="password" />

	<button type="submit">Login!</button>

</form>

<p>
	Don't have an account?  Why not <a href="/account/create">create one</a>!
</p>

<?php $this->load_view('common/footer'); ?>