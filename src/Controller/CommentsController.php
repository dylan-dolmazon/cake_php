<?php

namespace App\Controller;

use MongoDB\BSON\Timestamp;

class CommentsController extends AppController
{

    public function add($photo_id){
        if ($this->request->is('post')) {
            $entity = $this->Comments->newEntity($this->getRequest()->getData());
            $entity->photo_id = $photo_id;
            $entity->user_id = $this->getRequest()->getSession()->read('Auth.id');
            //$entity->date = new DateTime();
            $this->Comments->save($entity);
            $this->redirect(['controller' => 'Photos','action' => 'index']);
        }

    }

    public function supp($id){

        $comment = $this->Comments
            ->find()
            ->where(['id'=>$id])
            ->firstOrFail();

        if(($this->getRequest()->getSession()->read('Auth.id') == 1 && $this->getRequest()->getSession()->read('Auth.name') == 'admin')
            || $comment['user_id'] == $this->getRequest()->getSession()->read('Auth.id') && $comment != null ){
            $this->Comments->delete($comment);
        }
        $this->redirect(['controller' => 'Photos','action' => 'index']);
    }

}
