<?php
namespace App\Entity;

use JMS\Serializer\Annotation\Type;

use Doctrine\Common\Annotations;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Entity;
use Doctrine\ORM\Table;
use Doctrine\ORM\Id;
use Doctrine\ORM\Column;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordRepository")
 * @ORM\Table(name="record")
 */
class Record {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="phone_number")
     * @Type("libphonenumber\PhoneNumber")
     */
    private $phone;
    

    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    public function setName($name):Record
    {
        $this->name = $name;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }
    public function setPhone($phone):Record
    {
        $this->phone = $phone;
        return $this;
    }

    
    public function __toString()
    {
        return $this->name;
    }
}