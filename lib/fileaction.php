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
use \OCP\ILogger;

/**
 * Save user's files on pre-delete hook trigger
 */
Class FileAction
{
    protected $appName;
    protected $l;
    protected $config;

    public function __construct($appName, IL10N $l, IConfig $config, ILogger $logger)
    {
        $this->appName = $appName;
        $this->l = $l;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Move files from User before account deletion
     * @param \OC\User\User $user
     */
    public function saveUserFiles(\OC\User\User $user) 
    {
        // mv the files (<datadir>/uid)
        // TODO: quid du cas où un gros fichier est en train d'être uploadé / downloadé au moment du move ?
        if ($this->isUserBackupEnabled()) {
 
            // user ID (and name of user dir on owncloud)
            $uid = $user->getUID();

            // backup dir to store user's files before user deletion
            $userBackupDir = $this->getUserBackupDir();
            if (!file_exists($userBackupDir)) {
                mkdir($userBackupDir, 0700, true);
            }

            // owncloud data dir
            $OCDataDir = $this->getOCDataDir();

            $moveFrom = $OCDataDir . '/' . $uid;
            $moveTo = $userBackupDir . '/' . $uid . date('Ymd_His');

            if (file_exists($moveFrom) and !file_exists($moveTo)) {
                // $this->logger->info("Files backup before deletion for user $uid is done into $moveTo", array('app' => $this->appName));
                \OCP\Util::writeLog($this->appName, "Files backup before deletion for user $uid is done into $moveTo", \OCP\Util::INFO);
                rename($moveFrom, $moveTo);
            }

            // then re-create the 'uid' dir to no interfere with normal deletion process
            if (!file_exists($moveFrom)) {
                mkdir($moveFrom);
            }
        }
        else {
            $this->logger->error("Files backup before deletion has failed for user $uid", array('app' => $this->appName));
        }

    }

    /**
     * Get system conf for BackupFileBerforeUserDeletion
     */
    protected function isUserBackupEnabled()
    {
        $isUserBackupEnabled = \OCP\Config::getSystemValue('backup_file_before_user_deletion', FALSE);

        return $isUserBackupEnabled;
    }

    /**
     * Get system conf for UserBackupDir
     */
    protected function getUserBackupDir()
    {
        $userBackupDir = \OCP\Config::getSystemValue('backup_file_dir', '/tmp');

        return $userBackupDir;
    }

    /**
     * Get system conf for datadirectory
     */
    protected function getOCDataDir()
    {
        $dataDir = \OCP\Config::getSystemValue('datadirectory', \OC::$SERVERROOT.'/data');

        return $dataDir;
    }
}