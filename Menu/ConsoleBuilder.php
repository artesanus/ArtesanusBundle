<?php

namespace ArtesanIO\ArtesanusBundle\Menu;

use ArtesanIO\ArtesanusBundle\ArtesanusEvents;
use ArtesanIO\ArtesanusBundle\Event\ArtesanusMenuEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConsoleBuilder extends ContainerAware
{
    public function consoleMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array('childrenAttributes' => array('class' => 'nav navbar-nav')));

        $this->container->get('event_dispatcher')->dispatch(
            ArtesanusEvents::ARTESANUS_NAVBAR,
            new ArtesanusMenuEvent($factory, $menu)
        );

        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
    	$menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'nav navbar-nav');

        // foreach($this->itemsMainMenu($this->container->get('artesanus.managers')->getManagers()) as $i => $item){
        //
        //     $menu->addChild($i, array('route' => $item['alias']))
    	// 		->setAttribute('icon', 'icon-list');
        // }

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
    	$menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

    	/*
    	You probably want to show user specific information such as the username here. That's possible! Use any of the below methods to do this.

    	if($this->container->get('security.context')->isGranted(array('ROLE_ADMIN', 'ROLE_USER'))) {} // Check if the visitor has any authenticated roles
    	$username = $this->container->get('security.context')->getToken()->getUser()->getUsername(); // Get username of the current logged in user
    	*/


        // exit();

        $username = $this->container->get('security.context')->getToken()->getUser()->getUsername();

		$menu->addChild('User', array('label' => $username))
			->setAttribute('dropdown', true)
			->setAttribute('icon', 'icon-user')
            ->setAttribute('class', 'dropdown-toggle');

		$menu['User']->addChild('Profile', array('route' => 'users'))
			->setAttribute('icon', 'icon-edit');

            $menu['User']->addChild('Logout', array('route' => 'fos_user_security_logout'))
    			->setAttribute('icon', 'icon-edit');

        return $menu;
    }

    public function subMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'menu-section');

        $request = $this->container->get('request');
        $routeName = $this->container->get('artesanus.entity_prefix')->getEntityPrefix($request->get('_route'));

        $managers = $this->container->get('artesanus.managers');

        $package = '';

        foreach($managers->getManagers() as $manager){
            foreach($manager as $m){
                if($routeName === $m){
                    $package = $manager;
                }
            }
        }

        foreach($package as $p){
            $menu->addChild(ucwords($p), array('route' => $p))
    			->setAttribute('icon', 'icon-list');
        }


        //
		// $menu->addChild('Groups', array('route' => 'artesanus_console_acl_groups'))
		// 	->setAttribute('icon', 'icon-group');
        //
        //     $menu->addChild('Roles', array('route' => 'artesanus_console_acl_roles'))
    	// 		->setAttribute('icon', 'icon-list');
        return $menu;
    }

    public function aclMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	// $menu->setChildrenAttribute('class', 'menu-section');
        //
		// $menu->addChild('Users', array('route' => 'artesanus_console_acl_users'))
		// 	->setAttribute('icon', 'icon-list');
        //
		// $menu->addChild('Groups', array('route' => 'artesanus_console_acl_groups'))
		// 	->setAttribute('icon', 'icon-group');
        //
        //     $menu->addChild('Roles', array('route' => 'artesanus_console_acl_roles'))
    	// 		->setAttribute('icon', 'icon-list');
        return $menu;
    }

    public function usersNewMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'menu-plus');

		$menu->addChild('+', array('route' => 'artesanus_console_acl_users_new'))
			->setAttribute('icon', 'icon-list');
        return $menu;
    }

    public function groupsNewMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'menu-plus');

		$menu->addChild('+', array('route' => 'artesanus_console_acl_groups_new'))
			->setAttribute('icon', 'icon-list');
        return $menu;
    }

    public function rolesNewMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'menu-plus');

		$menu->addChild('+', array('route' => 'artesanus_console_acl_roles_new'))
			->setAttribute('icon', 'icon-list');
        return $menu;
    }

    // public function itemsMainMenu($managers)
    // {
    //     $menu = array();
    //     $packages = array();
    //
    //     foreach($managers as $item){
    //         if($item['package']){
    //             $packages[$item['package']] = $item['package'];
    //         }
    //     }
    //
    //     foreach ($packages as $package) {
    //         sort($managers);
    //         foreach($managers as $item){
    //             if($item['package'] == $package){
    //                 $menu[$package] = $item;
    //             }
    //         }
    //     }
    //
    //     return $menu;
    // }
}
