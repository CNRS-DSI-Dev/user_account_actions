<?php

/**
 * ownCloud - User Account Actions
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\User_Account_Actions\App;

use \OCP\AppFramework\App;

use \OCA\User_Account_Actions\Hooks\UserHooks;
use \OCA\User_Account_Actions\Lib\MailAction;

class User_Account_Actions extends App
{

    /**
     * Define your dependencies in here
     */
    public function __construct(array $urlParams=array())
    {
        parent::__construct('user_account_actions', $urlParams);

        $container = $this->getContainer();

        /**
         * Controllers
         */
        $container->registerService('UserHooks', function($c){
            return new UserHooks(
                $c->query('ServerContainer')->getUserManager(),
                $c->query('MailAction')
            );
        });

        /**
         * Lib
         */
        $container->registerService('MailAction', function($c) {
            return new MailAction(
                $c->query('AppName'),
                $c->query('L10N'),
                $c->query('Config')
            );
        });

        /**
         * Core
         */
        $container->registerService('Config', function($c) {
            return $c->query('ServerContainer')->getConfig();
        });

        $container->registerService('L10N', function($c) {
            return $c->query('ServerContainer')->getL10N($c->query('AppName'));
        });

    }


}