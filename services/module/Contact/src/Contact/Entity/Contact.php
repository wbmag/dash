<?php
namespace Contact\Entity;
use Doctrine\ORM\Mapping as ORM;

use User\Entity\Base;
use Zend\InputFilter\InputFilter;


/** @ORM\Entity */
class Contact 
{
  /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Workspace\Entity\Workspace",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="workspace_id", referencedColumnName="id")
     **/
    protected $workspace_id;
    
     /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user_id;
    
     
     
       public function exchangeArray($data = array()) 
    {
        foreach($data as $key => $value){
            $this->$key = $value;
        }
        return $this;
    }
      
}