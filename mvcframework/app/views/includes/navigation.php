<nav class="top-nav">
	<ul>
		<li>
			<a href="<?php echo URLROOT; ?>/pages/index">Home</a>
		</li>

		<li class="btn-login">
			<?php if (isset($_SESSION['user_id'])) : ?>
				<a href="<?php echo URLROOT; ?>/users/login">Log out</a>
			<?php else : ?>
				<a href="<?php echo URLROOT; ?>/users/login">Log in</a>
			<?php endif; ?>

		</li>

		<li class="btn-register">
			<a href="<?php echo URLROOT; ?>/users/register">Register</a>
		</li>
	</ul>
	
</nav>