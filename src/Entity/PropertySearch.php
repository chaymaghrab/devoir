<?php
namespace App\Entity;
class PropertySearch
{/**
     * @ORM\Column(type="string", length=255)
     */
private $Name;

public function getName():? string
{
   return $this->Name;
}
public function setName( string $Name): self
{
$this->Name=$Name;
return$this;
}
}