</div><!-- content -->

<?php if($this->session->get('user', false)): ?>
	<p style="margin-left: 80%;">
		<label><a href="/logout">Logout</a> <?=$this->session->get('user')?></label>
	</p>
<?php endif; ?>

</div><!-- container -->


</body>
</html>