<?php
namespace App\Entity;

use JMS\Serializer\Annotation\Type;

use Doctrine\Common\Annotations;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Entity;
use Doctrine\ORM\Table;
use Doctrine\ORM\Id;
use Doctrine\ORM\Column;

use FOS\UserBundle\Model\User as FOSUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends FOSUser {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId():integer 
    {
        return $this->id;
    }
}