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
 * Send mail on hook trigger
 */
Class MailAction
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
     * Send a mail signaling user creation
     * @param \OC\User\User $user
     */
    public function mailUserCreation(\OC\User\User $user)
    {
        $toAddress = $toName = $this->config->getSystemValue('monitoring_admin_email');

        $now = new \DateTime();
        $niceNow = date_format($now, 'd/m/Y H:i:s');
        $subject = (string)$this->l->t('User %s just has been created (%s)', array($user->getUID(), $niceNow));

        $html = new \OCP\Template($this->appName, "mail_usercreation_html", "");
        $html->assign('userUID', $user->getUID());
        $html->assign('datetime', $niceNow);
        $html->assign('home', $user->getHome());
        $htmlMail = $html->fetchPage();

        $alttext = new \OCP\Template($this->appName, "mail_usercreation_text", "");
        $alttext->assign('userUID', $user->getUID());
        $alttext->assign('datetime', $niceNow);
        $alttext->assign('home', $user->getHome());
        $altMail = $alttext->fetchPage();

        $fromAddress = $fromName = \OCP\Util::getDefaultEmailAddress('owncloud');

        try {
            \OCP\Util::sendMail($toAddress, $toName, $subject, $htmlMail, $fromAddress, $fromName, 1, $altMail);
        } catch (\Exception $e) {
            \OCP\Util::writeLog('user_account_actions', "Can't send mail for user creation: " . $e->getMessage(), \OCP\Util::ERROR);
        }
    }

    /**
     * Send a mail signaling a user deletion
     * @param \OC\User\User $user
     */
    public function mailUserDeletion(\OC\User\User $user)
    {
        $toAddress = $toName = $this->config->getSystemValue('monitoring_admin_email');

        $now = new \DateTime();
        $niceNow = date_format($now, 'd/m/Y H:i:s');
        $subject = (string)$this->l->t('User %s just has been deleted (%s)', array($user->getUID(), $niceNow));

        $html = new \OCP\Template($this->appName, "mail_userdeletion_html", "");
        $html->assign('userUID', $user->getUID());
        $html->assign('datetime', $niceNow);
        $htmlMail = $html->fetchPage();

        $alttext = new \OCP\Template($this->appName, "mail_userdeletion_text", "");
        $alttext->assign('userUID', $user->getUID());
        $alttext->assign('datetime', $niceNow);
        $altMail = $alttext->fetchPage();


        $fromAddress = $fromName = \OCP\Util::getDefaultEmailAddress('owncloud');

        try {
            \OCP\Util::sendMail($toAddress, $toName, $subject, $htmlMail, $fromAddress, $fromName, 1, $altMail);
        } catch (\Exception $e) {
            \OCP\Util::writeLog('user_account_actions', "Can't send mail for user deletion: " . $e->getMessage(), \OCP\Util::ERROR);
        }
    }
}