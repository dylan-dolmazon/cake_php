<?php
$this->assign('title','register');
echo $this->Form->create(null, [
    'type' => 'post'
]);
echo $this->Form->control('email',[
    'name' => 'email'
]);

echo $this->Form->control('name',[
    'name'=> 'name'
]);

echo $this->Form->control('password',[
    'label' => 'votre mot de passe',
    'name' => 'password'
]);
echo $this->Form->button('register');
echo $this->Form->end();


