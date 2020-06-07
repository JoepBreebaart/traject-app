<?php 
require_once 'core/init.php';


if(Session::exists('home')) {
    echo Session::flash('home');
}

$user = new User(); 
// echo $user->data()->email;

if($user->isLoggedIn()) {
	?>
		<p>Hello <a href="profile.php?user=<?php echo escape($user->data()->id); ?>">
			<?php echo escape($user->data()->voornaam); ?></a>!</p>

		<ul>
			<li><a href="logout.php">Logout</a></li>
			<li><a href="update.php">Update</a></li>
			<li><a href="changepassword.php">Change Password</a></li>
		</ul>
	<?php

	// if($user->hasPermission('admin')) {
	// 	echo '<p>You are an admin.</p>';
	// }

} else {
	echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register</a>.</p>';
}