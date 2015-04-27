<?php
namespace User\Controller;

use User\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Validator\File\UploadFile;


use Zend\Mail;

class UserController extends AbstractRestfulJsonController{

    protected $em;
    protected $authservice;
    protected $storage;

    public function getEntityManager(){
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction(){
        $users = $this->getEntityManager()->getRepository('User\Entity\User')->findAll();
        
        $users = array_map(function($user){
            return $user->toArray();
        }, $users);
        return new JsonModel($users);
    }
    
     public function getSessionStorage() {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                                  ->get('User\Model\MyAuthStorage');
        }

        return $this->storage;
    }
    
    public function loginAction(){                    
        $data = $this->getRequest()->getContent();
        $data = (!empty($data))? get_object_vars(json_decode($data)) : '';
        $user = new \User\Entity\User($data);
        if($user->validateLogin($this->em)){
            $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
            $adapter = $authService->getAdapter();
            $adapter->setIdentityValue($data['email']);
            $adapter->setCredentialValue($data['password']);
            $authResult = $authService->authenticate();
            if ($authResult->isValid()) {
                $identity = $authResult->getIdentity();
                $authService->getStorage()->write($identity);
                $identity = $authResult->getIdentity();
                $logged_in_user_details = $identity->toArray();
                return new JsonModel(array('status'=>'success','data'=>$logged_in_user_details));
            } else {
                return new JsonModel(array('status'=>'error','data'=>array('message'=>'Invalid details')));
            }
        } 
    }
       
   
    public function getList(){   
        // Action used for GET requests without resource Id
        $users = $this->getEntityManager()->getRepository('User\Entity\User')->findAll();
        $users = array_map(function($user){
            return $user->toArray();
        }, $users);
        return new JsonModel($users);
    }

    public function get($id){   
        // Action used for GET requests with resource Id
        $user = $this->getEntityManager()->getRepository('User\Entity\User')->find($id);
        return new JsonModel(
            $user->toArray()
        );
    }

    public function getMyUsersAction(){
        return $this->getList();
    }

    public function registerAction(){
        $data = $this->getRequest()->getContent();
        $data = (!empty($data))? get_object_vars(json_decode($data)) : '';
        $path = 'public/img/apple.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $image = file_get_contents($path);
        $image = 'data:image/' . $type . ';base64,' . base64_encode($image);
        
        if (strpos($image,'jpg') !== false) 
            $extention = "jpg";
        
        if (strpos($image,'jpeg') !== false) 
            $extention = "jpeg";
        
        if (strpos($image,'png') !== false) 
            $extention = "png";
        
        if (strpos($image,'base64,') !== false) {
        $image = substr($image, strpos($image, "base64,") );  
        $image = str_replace('base64,', '', $image);
        $image = str_replace(' ', '+', $image); 
        $image = base64_decode($image);
        $file = 'public/photo/'. "123" .'.'. $extention;
        $success = file_put_contents($file, $image);
        } else
        return new JsonModel(array('status'=>'Image format is not proper'));
       /*  $content = '<p>Dear '.$data['name'].',</p>';
         $content .= '<p>Your account has been created please click the link to change password.</p>';
         $content .= '<p>http://127.0.0.1:8000/#/set-password/' 1 '</p>';
        // $content .= '<p>Password : '.$getdata[0]['password'].'</p>';
         $content .= '<p></p>';
         $plugin = $this->SendEmailPlugin();
         $plugin->sendemail($content, 'donotreply@dash.com', $data['email'], 'DASH : Account conformation', true);*/
   
     
       /*if (strpos($image,'jpg') !== false) {
            $extention = "jpg";
            $image = str_replace('data:image/jpg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            }
        if (strpos($image,'jpeg') !== false) {
            $extention = "jpeg";
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $data);
            }
        if (strpos($image,'png') !== false) {    
            $extention = "png";
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            }

        $image = base64_decode($image);*/
        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";
       /* $file = 'public/photo/'. "123" .'.'. $extention;
        $success = file_put_contents($file, $image);*/
        $this->getEntityManager();                   
        $hash_string = $data['email'].date('Y-m-d H:i:s');
        $hash = md5($hash_string);
        $data['hash'] = $hash;
        $data['role'] = 'user';
       
        $user = new \User\Entity\User($data);        
        if($user->validate($this->em)){
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();  
        $em = $this->getEntityManager();
         $queryBuilder = $em->createQueryBuilder();
         $queryBuilder->select('o.id')->from('User\Entity\User', 'o')
                 ->where('o.email = :emailaddress')
                 ->setParameter('emailaddress', $data['email']);
         $result = $queryBuilder->getQuery();
        // print_r($result);exit();
         $getdata=$result->getResult(); 
         $content = '<p>Dear '.$data['name'].',</p>';
        $content .= '<p>You got invitation from  dash please click the below link to reset the  password.</p>';
        $content .= '<p>http://127.0.0.1:8000/#/set-password/'  .$getdata[0]['id'] .'</p>';
        //$content .= '<p>Password : '.$getdata[0]['password'].'</p>';
         $content .= '<p></p>';
         $plugin = $this->SendEmailPlugin();
         $plugin->sendemail($content, 'donotreply@dash.com', $data['email'], 'DASH : Account conformation', true);
              return new JsonModel(array('status'=>$getdata));
//          
        } 
    }
    
     
    public function update($id){
        // Action used for PUT requests
        $data = $this->getRequest()->getContent();
        $data = (!empty($data))? get_object_vars(json_decode($data)) : '';
        $user = $this->getEntityManager()->getRepository('User\Entity\User')->find($id);
        $user->set($data);
        $user->validate($this->em);        
        $this->getEntityManager()->flush();        
        return new JsonModel($user->toArray());
    }

    public function delete($id){
        // Action used for DELETE requests
        $user = $this->getEntityManager()->getRepository('User\Entity\User')->find($id);
        $this->getEntityManager()->remove($user);        
        $this->getEntityManager()->flush();        
        return new JsonModel($user->toArray());
    }
    
     public function forgotPasswordAction(){     
         $data = $this->getRequest()->getContent();
         $data = (!empty($data))? get_object_vars(json_decode($data)) : '';
         $emailaddress1 = $data;
         //print_r( $emailaddress1);
         if(($emailaddress1['email']=="")){
             return new JsonModel(array('status'=>'Enter email address','data'=>array()));
         }
         $emailaddress=$emailaddress1['email'];
         $em = $this->getEntityManager();
         $queryBuilder = $em->createQueryBuilder();
         $queryBuilder->select('o.username,o.password,o.email,o.user_id')->from('User\Entity\User', 'o')
                 ->where('o.email = :emailaddress')
                 ->setParameter('emailaddress', $emailaddress);
         $result = $queryBuilder->getQuery();
        // print_r($result);exit();
         $getdata=$result->getResult(); 
         //print_r($getdata); exit();
        if(!empty($getdata)){
         //print_r($getdata); exit();       
         $content = '<p>Dear '.$getdata[0]['username'].',</p>';
         $content .= '<p>Password details are below.</p>';
         $content .= '<p>Username : '.$getdata[0]['username'].'</p>';
         $content .= '<p>Password : '.$getdata[0]['password'].'</p>';
         $content .= '<p></p>';
         $plugin = $this->SendEmailPlugin();
         $plugin->sendemail($content, 'donotreply@dash.com', $emailaddress, 'DASH : Forgot Password', true);
       
         // echo json_encode($getdata);
         return new JsonModel(array('status'=>'ok','data'=>array()));
         }         
         else{
             return new JsonModel(array('status'=>'not ok','data'=>array()));
     }
             
    }
    public function logoutAction(){
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $authService->clearIdentity();
        return new JsonModel(array('status'=>'success'));
    }
    
    public function invalidAccessAction() {
         return new JsonModel(array('status'=> '401','data'=>'invalid access'));
    }

    public function resetPasswordAction(){
         $data = $this->getRequest()->getContent();
         $data = (!empty($data))? get_object_vars(json_decode($data)) : '';
            if(!empty($data['new_password']) && !empty($data['confirm_password'])){
                $new_password = $data['new_password'];
                $confirm_password = $data['confirm_password'];
                //print_r($confirm_password); exit();
                if($new_password == $confirm_password){
                    $logged_in_user_details = array();
                    $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                    if($authService->hasIdentity()){
                        $user = $authService->getIdentity();
                        $logged_in_user_details = $user->toArray();            
                    }
                    $id=$logged_in_user_details['id'];
                    $email=$logged_in_user_details['email'];
                    $hash_string = $email.date('Y-m-d H:i:s');
                    $hash = md5($hash_string);
                    $user = $this->getEntityManager()->getRepository('User\Entity\User')->find($id);
                    $candidateEntity = $user->exchangeArray(array('password'=>$new_password, 'hash'=>$hash));
                    $user->set($candidateEntity);       
                     $this->getEntityManager()->persist($user);
                     $this->getEntityManager()->flush();    
                     return new JsonModel(array('status'=>'success'));
                } else {
                    return new JsonModel(array('status'=>'error','data'=>array('message'=>'Password didnt match')));
                }
            } else {
                return new JsonModel(array('status'=>'error','data'=>array('message'=>'Invalid inputs')));
            }
        
    }
    
      public function suggestAction(){     
         $request = $this->getRequest();
         $getParams = $request->getQuery();
         $name = $getParams['username'];
         $em = $this->getEntityManager();
         $queryBuilder = $em->createQueryBuilder();
         $queryBuilder->select('o.username,o.photo')->from('User\Entity\User', 'o')
         ->where('o.username LIKE :usernamelike')
         ->setParameter('usernamelike', "$name%");
         $result = $queryBuilder->getQuery();
         $getdata=$result->getResult();          
         echo json_encode($getdata);
         return new JsonModel(array('status'=>'ok'));
//         $json_formt=  json_encode($getdata);
//         print_r($json_formt);
//         return new JsonModel(array('status'=>'ok'));                 
    }
    
    
    public function displayimage(){     
         $request = $this->getRequest();
         $getParams = $request->getQuery();
         $name = $getParams['username'];
         $em = $this->getEntityManager();
         $queryBuilder = $em->createQueryBuilder();
         $queryBuilder->select('o.username,o.photo')->from('User\Entity\User', 'o')
         ->where('o.username LIKE :usernamelike')
         ->setParameter('usernamelike', "$name%");
         $result = $queryBuilder->getQuery();
         $getdata=$result->getResult();          
         echo json_encode($getdata);
         return new JsonModel(array('status'=>'ok'));
//         $json_formt=  json_encode($getdata);
//         print_r($json_formt);
//         return new JsonModel(array('status'=>'ok'));                 
    }
    public function updateProfileAction(){
        unlink("public/photo/123");
        $data = $this->getRequest()->getContent();
        $data = (!empty($data))? get_object_vars(json_decode($data)) : '';
        $image = $data['photo'];
        if (strpos($image,'base64,') !== false) {
        $image = substr($image, strpos($image, "base64,") );  
        $extention = "jpg";
        $image = str_replace('base64,', '', $image);
        $image = str_replace(' ', '+', $image); 
        $image = base64_decode($image);
        $file = 'public/photo/'. "123" .'.'. $extention;
        $success = file_put_contents($file, $image);
        } else
        return new JsonModel(array('status'=>'Image format is not proper'));
    }
    
   
}