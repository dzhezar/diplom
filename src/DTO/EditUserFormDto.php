<?php


namespace App\DTO;


class EditUserFormDto
{
    private $name;
    private $roles;

    public function __construct(?string $name = null, ?string $roles = null)
    {
        $this->name = $name;
        $this->roles = $roles;

    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getRoles(): ?string
    {
        return $this->roles;
    }

    /**
     * @param string|null $roles
     */
    public function setRoles(?string $roles): void
    {
        $this->roles = $roles;
    }
}
