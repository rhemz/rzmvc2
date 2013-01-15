<?php $this->load_view('common/header', array('title' => 'Create an account')); ?>

<form action="" method="post">

	<label for="username"><?=$val->error('username') ? $val->message('username') : 'Username'?></label>
	<input type="text" id="username" name="username" value="<?=$val->value('username')?>" />

	<label for="email"><?=$val->error('email') ? $val->message('email') : 'Email address'?></label>
	<input type="text" id="email" name="email" value="<?=$val->value('email')?>" />

	<label for="password1"><?=$val->error('password1') ? $val->message('password1') : 'Create your password'?></label>
	<input type="password" id="password1" name="password1" />

	<label for="password2"><?=$val->error('password2') ? $val->message('password2') : 'Confirm your password'?></label>
	<input type="password" id="password2" name="password2" />


	<button type="reset">Reset</button>
	<button type="submit">Register</button>
	
</form>

<p>
	<a href="/">Go back home</a>
</p>

<?php $this->load_view('common/footer'); ?>