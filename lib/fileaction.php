<?php

/**
 * ownCloud - User_Account_Actions
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\User_Account_Actions\Lib;

use \OCP\IL10N;
use \OCP\IConfig;

/**
 * Save user's files on pre-delete hook trigger
 */
Class FileAction
{
    protected $appName;
    protected $l;
    protected $config;

    public function __construct($appName, IL10N $l, IConfig $config)
    {
        $this->appName = $appName;
        $this->l = $l;
        $this->config = $config;
    }

    /**
     * Move files from User before account deletion
     * @param \OC\User\User $user
     */
    public function saveUserFiles(\OC\User\User $user) {
    	// mv the files (<datadir>/uid) 
    	// then re-create the 'uid' dir to no interfere with normal deletion process
    }
}