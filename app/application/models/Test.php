<?php


namespace Models;


class Test 
{

	public function getId()
	{
		return $this->data["id"];
	}
	public function getName()
	{
		return $this->data["name"];
	}
	public function getEdad()
	{
		return $this->data["edad"];
	}

	public function setName($name)
	{
		$this->data["name"] = $name;
		return $this;
	}
	public function setEdad($edad)
	{
		$this->data["edad"] = $edad;
		return $this;
	}

	protected $data = ["id"   => null,
				    "name" => "name",
				    "edad" => 0];
}



