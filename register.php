<?php 
require_once 'core/Init.php';

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
            'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 64,
                'unique' => 'trainees'
            ),
            'password' => array(
                'required' => true,    
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'voornaam' => array(
                'required' => true,
                'min' => 2,
                'max' => 45
            )
            // 'achternaam' => array(
            //     'required' => true,
            //     'min' => 2,
            //     'max' => 45
            // )
        ));
        
        if($validation->passed()) {
            $user = new User();

            $salt = Hash::salt(16);  
                   
            try {
                $user->create('trainees', array(
                    'email'	=> Input::get('email'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'voornaam' => Input::get('voornaam'),
                    'achternaam' => 'breebaart',
                    'loopbaan' => 'overheid iets',
                    'roadmap_status' => 2
                    // 'trajectbegeleider_fk' => Input::get('trajectbegeleider_fk')

                ));

                Session::flash('home', 'Netjes geregistreerd al zeg ik het zelf.');              
                Redirect::to(404);

            } catch(Exception $e) {
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
        <label for="email">email</label>
        <input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off" />
    </div>
    
    <div class="field">
        <label for="password">wachtwoord</label>
        <input type="password" id="password" name="password">
    </div>
    
    <div class="field">
        <label for="password_again">wachtwoord opnieuw</label>
        <input type="password" id="password_again" name="password_again">
    </div>
    
    <div class="field">
        <label for="voornaam">voornaam</label>
        <input type="text" name="voornaam" id="voornaam" value="<?php echo escape(Input::get('voornaam')); ?>">
    </div>
    
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Register">
    
</form>