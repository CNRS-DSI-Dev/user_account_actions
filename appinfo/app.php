<?php
/**
 * ownCloud - User Account Actions
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */
namespace OCA\User_Account_Actions\AppInfo;

use \OCA\User_Account_Actions\App\User_Account_Actions;

$app = new User_Account_Actions();
$app->getContainer()->query('UserHooks')->register();
