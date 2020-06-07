<?php

require_once 'core/init.php';
$user = new User();
$result = $user->find('trainees', 13);

echo $result;