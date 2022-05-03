<?php

echo '<h1>Voici tous les utilisateurs</h1>';

if (!empty($users)) {
    foreach ($users as $user){
        echo '<h3> utilisateur name :'.$user['name'].'</h3>';
        echo '<p> utilisateur id :'.$user['id'].'</p>';
        echo '<p> utilisateur email :'.$user['email'].'</p>';
        echo '<br>';
    }
}

$this->assign('title','Delete user');
echo $this->Form->create(null);
echo $this->Form->control('reason');
echo $this->Form->control('id');
echo $this->Form->button('DELETE');
echo $this->Form->end();
