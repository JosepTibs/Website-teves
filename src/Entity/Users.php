<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Fname = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Mname = null;

    #[ORM\Column(length: 50)]
    private ?string $Lname = null;

    #[ORM\Column(length: 50)]
    private ?string $Email = null;

    #[ORM\Column(length: 100)]
    private ?string $password = null;

    #[ORM\Column(length: 11)]
    private ?string $phone_num = null;   // ✅ changed from int to string

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFname(): ?string
    {
        return $this->Fname;
    }

    public function setFname(string $Fname): static
    {
        $this->Fname = $Fname;
        return $this;
    }

    public function getMname(): ?string
    {
        return $this->Mname;
    }

    public function setMname(?string $Mname): static
    {
        $this->Mname = $Mname;
        return $this;
    }

    public function getLname(): ?string
    {
        return $this->Lname;
    }

    public function setLname(string $Lname): static
    {
        $this->Lname = $Lname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPhoneNum(): ?string   
    {
        return $this->phone_num;
    }

    public function setPhoneNum(string $phone_num): static 
    {
        $this->phone_num = $phone_num;
        return $this;
    }


}
