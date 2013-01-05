<form action="" method="post">

	<p>Username</p>
	<p> <input name="username" value="<?=$val->value('username')?>" type="text" id="form_username" /> </p>

	<p>Password</p>
	<p> <input name="password" value="<?=$val->value('password')?>" type="password" id="form_password" /> </p>

	<p>
		<input name="reset" value="Reset" type="reset" id="form_reset" />
		<input name="submit" value="Log in" type="submit" id="form_submit" />
	</p>

</form>

<?php Logger::print_r($val->messages); ?>