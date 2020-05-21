<?php


namespace App\Mapper;


use App\DTO\EditUserFormDto;
use App\Entity\User;

class UserMapper
{
    public function entityToFormDto(User $user)
    {
        return new EditUserFormDto(
            $user->getName(),
            $user->getRoles()[0]
        );
    }

    public function formDtoToEntity(EditUserFormDto $formDto, User $user)
    {
       return $user->setRoles([$formDto->getRoles()])
           ->setName($formDto->getName());
    }
}
