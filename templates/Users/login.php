<?php
$this->assign('title','login');
echo $this->Form->create(null, [
    'type' => 'post'
]);
echo $this->Form->control('email',[
    'name' => 'email'
]);

echo $this->Form->control('password',[
    'label' => 'votre mot de passe',
    'name' => 'password'
]);
echo $this->Form->button('login');
echo $this->Form->end();

echo $this->Html->link(
    'register',
    ['controller' => 'Users','action'=>'register']
);

