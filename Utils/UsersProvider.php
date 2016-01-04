<?php

namespace ArtesanIO\ArtesanusBundle\Utils;

use ArtesanIO\ArtesanusBundle\Model\UsersBase;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UsersProvider implements UserProviderInterface
{

    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function loadUserByUsername($user)
    {
        try{
            $userManager = $this->container->get('artesanus.users_manager');

            return $userManager->getRepository()->findUsernameOrEmail($user);

        }catch (NoResultException $e){
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UsersBase) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'ArtesanIO\ArtesanusBundle\Entity\Users';
    }
}