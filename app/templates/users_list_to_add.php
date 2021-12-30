<?php
foreach ($users_list as $user) {
	?>
	<li data-id="<?php echo trim($user['wp_user_id']) ?>"><?php echo trim($user['first_name']) . ' ' . trim($user['last_name'])  ?> <span>x</span></li>
	<?php
}
