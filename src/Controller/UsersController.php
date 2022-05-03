<?php

namespace App\Controller;

use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Http\Session;

/**
 * @property AuthenticationComponent $Authentication
 * @property UsersController $Users
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->Authentication->allowUnauthenticated(['login','signin','Photos' => 'view',"Users" => 'register']);
    }

    public function index(){

        $user = $this->Users-find()
            ->contain(['Todas', 'Profiles'])
            ->all();

        dd($user);
    }

    public function login(){

        $user = $this->Users->newEmptyEntity();

        $result = $this->Authentication->getResult();

        if($result->isValid()){
            $target = $this->Authentication->getLoginRedirect() ?? '/';
            return $this->redirect($target);
        }
        if($this->getRequest()->is('Post') && !$result->isValid()){
            $this->Flash->error('mot de passe non valide');
        }

        $this->set(compact('user'));

    }

    public function register(){

        $user=$this->Users->newEmptyEntity();
        if(!empty($this->getRequest()->getData())){
            $result=$this->Authentication->getResult();
            $name=$this->getRequest()->getData('name');
            $email=$this->getRequest()->getData('email');
            $password=$this->getRequest()->getData('password');
            if($this->verifMailExist($email)){
                $this->Flash->error("l'adresse mail existe dejà");
                return $this->redirect($this->referer());
            }
            $tabData=["name"=>$name,"email"=>$email,"password"=>$password];
            $this->Users->patchEntity($user,$tabData);
            if($this->Users->save($user)){
                $this->Flash->success("compte créé");
                $target = $this->Authentication->getLoginRedirect() ?? '/';
                return $this->redirect($target);
            }
        }
        $this->set(compact('user'));
    }

    public function logout(){
        $this->Authentication->logout();
        $target = '/';
        return $this->redirect($target);
    }
    /*
    public function signin(){
        $user = ['email' => 'test@gmail.com', 'password' => '1234'];
        $userEntity = $this->Users->newEntity($user);
        $this->Users->save($userEntity);
        die('ok');
    }
*/
    public function delete(){

        if($this->getRequest()->getSession()->read('Auth.id') == 1 && $this->getRequest()->getSession()->read('Auth.name') == 'admin') {
            $users = $this->Users
                ->find()
                ->where(['id !=' => 1])
                ->toArray();
        }
        $this->set(compact('users'));

        if($this->request->getData('reason')){
            $id = $this->request->getData('id');
            $users = $this->Users
                ->find()
                ->where(['id' => $id])
                ->firstOrFail();

            $this->Users->delete($users);
            $this->redirect('/');
        }
    }

    private function verifMailExist($emailToAdd): bool
    {
        $email=$this->Users->find()->select(['id'])->where(['email'=>$emailToAdd])->toArray();
        if(empty($email)){
            return false;
        }else{
            return true;
        }

    }
}
