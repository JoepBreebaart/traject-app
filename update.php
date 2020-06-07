<?php 
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'voornaam' => array(
				'required' => true,
				'min' => 2,
				'max' => 64
			)
		));

		if($validation->passed()) {
			// update
			try {
				$user->update(array(
					'voornaam' => Input::get('voornaam')
				));
				Session::flash('home', 'Your details have been updated.');
				Redirect::to('index.php');
			}catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			foreach($validation->errors() as $error) {
				echo $error, '<br>';
			}
		}
	}
}

?>

<form action="" method="post">
	<div class="field">
		<label for="name">voornaam</label>
		<input type="text" name="voornaam" value="<?php echo escape($user->data()->voornaam); ?>">
	</div>
	<input type="submit" value="Update">
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>