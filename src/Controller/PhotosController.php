<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Integer;
use Psr\Log\NullLogger;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\isEmpty;
use function React\Promise\all;

class PhotosController extends AppController
{
    public function view()
    {


        if(!empty($this->getRequest()->getQuery('page'))){
            $page = (int) $this->getRequest()->getQuery('page')?:1;
            $out= $this->Photos
                ->find()
                ->limit(5)
                ->page($page)
                ->contain(['Comments'])
                ->toArray();
            return $this->response->withStringBody(json_encode($out))->withType("application/json");
        }
        $nb = $this->getRequest()->getQuery('limit');
        $name = $this->getRequest()->getQuery('name');

        if($name != null){
            $out = $this->searchPhoto($name);

            $com = $this->Photos
                ->find()
                ->where(['id' => $out['id']])
                ->contain(['Comments'])
                ->first();
            $out = $com;
            //debug($out);
        }else{
            $files = glob(WWW_ROOT.'img/*.jpg');

            $cpt=0;
            $out = [];

            if(!$files){
                return $this->response->withStringBody(json_encode(['Error'=>'fichier introuvable']))->withType("application/json")->withStatus(404);
            }
            foreach ($files as $value){

                $exif = exif_read_data($value);

                $desc=$exif['ImageDescription']??'no-desc';
                $result = [
                    'filename' => $exif['FileName']??'no-file',
                    'description' =>$exif['ImageDescription']??'no-Description',
                    'Comment'=> $exif['UsersComment']??'no-comment',
                    'author'=>$exif['Author']??'no-authors',
                    'width'=>$exif['RelatedImageWidth']??'no-width',
                    'height'=>$exif['RelatedImageHeight']??'no-height',
                    'html'=>'<img src=\"'.$exif["FileName"].'\" alt=\"'.$desc.'\">',
                ];

                $out[] = $result;
                $cpt++;

                if($cpt==$nb){
                    break;
                }
            }
        }

        return $this->response->withStringBody(json_encode($out))->withType("application/json");
    }

    public function index(){

        $files = $this->tabPhotos();
        $cpt = $this->getRequest()->getQuery('pageNumber')??0;
        $cpt *= 8;
        $nbPhotos = $this->maxPhotos();
        $this->set(compact('nbPhotos'));
        if($cpt > $nbPhotos){$cpt = 0;}
        $this->set(compact('cpt'));
        $this->set(compact('files'));

    }

    public function description(){

        $name = $this->getRequest()->getQuery('id')??"Canon_40D.jpg";
        $photo = $this->searchPhoto($name);

        $com = $this->Photos
            ->find()
            ->where(['id' => $photo['id']])
            ->contain(['Comments'])
            ->first();
        $photo = $com;
        $this->set(compact('photo'));
    }

    private function maxPhotos() :int{

        $cpt = $this->Photos
            ->find()
            ->count();
        return $cpt;

    }

    private function tabPhotos(){

        $files = $this->Photos
            ->find()
            ->all()
            ->toArray()
        ;
        return $files;

    }

    private function getPhoto($name){
        return $this->Photos->find()->where(['name' => $name])->first();
    }

    private function searchPhoto($name){

        $file = $this->getPhoto($name);

        $result = [
            'filename' => $file['name']??'no-file',
            'description' =>$file['description']??'no-Description',
            'width'=>$file['width']??'no-width',
            'height'=>$file['height']??'no-height',
            'id'=>$file['id']
        ];
        return $result;
    }

    private function searchPicture($pictureSearch): bool
    {

        $file = $this->getPhoto($pictureSearch);
        if(!$file){
            return false;
        }else{
            return true;
        }
    }

    public function add(){

        if ($this->request->is('post')) {
            $NamePicture = $this->request->getData('img');
            if (!$this->searchPicture($NamePicture->getClientFileName())) {
                $entity = $this->Photos->newEntity($this->getRequest()->getData());
                $entity->author = $this->getRequest()->getSession()->read('Auth.name');
                $entity->user_id = $this->getRequest()->getSession()->read('Auth.id');
                $destination = WWW_ROOT . 'img/' . $NamePicture->getClientFilename();
                $NamePicture->moveTo($destination);
                $this->Flash->success("photo ajouté");
                if($this->Photos->save($entity)){
                    $this->redirect('/');
                }

            } else {
                $this->Flash->error("Photo non sauvegardé");
            }
        }
    }

    public function suppPhoto(){

        if ($this->request->is('post')) {
            $NamePicture = $this->request->getData('name');
            $photo = $this->Photos->find()->where(['name' => $NamePicture])->first();
            if($photo != null){
                $this->Photos->delete($photo);
            }
            else{
                $this->Flash->error("Photo non trouvé");
            }

        }

    }

    public function default(){

        $files = $this->Photos
            ->find()
            ->all()
            ->toArray()

        ;
        $this->set(compact('files'));
    }

    public function myImage($id){

        $photo = $this->Photos
            ->find()
            ->where(['id'=>$id , 'user_id' => $this->getRequest()->getSession()->read('Auth.id')])
            ->contain(['Comments'])
            ->firstOrFail();

        if ($this->request->getData('description')) {
            $this->Photos->patchEntity($photo, $this->request->getData());
            $this->Photos->save($photo);


        }
        $this->set(compact('photo'));

    }

    public function deleteMyImage($id){

        if($this->getRequest()->getSession()->read('Auth.id') == 1 && $this->getRequest()->getSession()->read('Auth.name') == 'admin'){
            $photo = $this->Photos
                ->find()
                ->where(['id'=>$id])
                ->contain(['Comments'])
                ->firstOrFail();
            $this->set(compact('photo'));
        }else{
            $photo = $this->Photos
                ->find()
                ->where(['id'=>$id , 'user_id' => $this->getRequest()->getSession()->read('Auth.id')])
                ->contain(['Comments'])
                ->firstOrFail();
            $this->set(compact('photo'));
        }


        if($this->request->getData('reason')){
            $this->Photos->delete($photo);
            unlink(WWW_ROOT . 'img/' . $photo['name']);
            $this->redirect('/');
        }
    }

    public function showComments($id){

        $photo = $this->Photos
            ->find()
            ->where(['id'=>$id , 'user_id' => $this->getRequest()->getSession()->read('Auth.id')])
            ->contain(['Comments'])
            ->firstOrFail();

        $this->set(compact('photo'));

    }

    //ajouter les photos a la base de donnée
/*
    public function once(){
        $files =$this->tabPhotos();

        foreach ($files as $file) {
            $exif = exif_read_data($file);
            $photo = $this->Photos->newEmptyEntity();
            $photo->name = $exif['FileName'];
            if(!array_key_exists('ImageDescription',$exif)){
                $photo->description = 'no-desc';
            }else{
                $photo->description = $exif['ImageDescription'];
            }
            $photo->width = $exif['ExifImageWidth'];
            $photo->heigth = $exif['ExifImageLength'];
            if($this->Photos->save($photo));
        }

    }*/

    //function for add in weebroot img
    /*public function add(){
        if ($this->request->is('post')) {

            $NamePicture = $this->request->getData('img');
            dd($NamePicture);
            if (!$this->searchPicture($NamePicture->getClientFileName())) {
                $uploadPath = glob(WWW_ROOT . 'img/');
                $destination = $uploadPath[0] . $NamePicture->getClientFilename();
                $NamePicture->moveTo($destination);
                $this->Flash->success("photo ajouté");

            } else {
                $this->Flash->error("Photo non sauvegardé");
            }
        }
    }*/
}





