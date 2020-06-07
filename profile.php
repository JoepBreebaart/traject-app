  
<?php

require_once 'core/init.php';

if(!$email = Input::get('user')) {
	Redirect::to('index.php');
} else {
	$user = new User($email);
	if(!$user->exists()) {
		Redirect::to(404);
	} else {
		// user exists
		$data = $user->data();
	}
	?>
	
	<h3><?php echo escape($data->email); ?></h3>
	<p>Full name: <?php echo escape($data->voornaam). ' ' . escape($data->achternaam);?></p>

	<?php 
}
?>