<?php
namespace OCA\User_Account_Actions\Hooks;

class UserHooks {

    protected $userManager;
    protected $mailAction;
    protected $fileAction;

    public function __construct($userManager, $mailAction, $fileAction){
        $this->userManager = $userManager;
        $this->mailAction = $mailAction;
        $this->fileAction = $fileAction;

    }

    public function postCreateUser(\OC\User\User $user) {
        $this->mailAction->mailUserCreation($user);

        // if user's uid (username) is a email, additional treatments :
        if(filter_var($user->getUID(), FILTER_VALIDATE_EMAIL)) {

            // add the email on oc_preferences
            \OC_Preferences::setValue($user->getUID(), 'settings', 'email', $user->getUID());

            // add the IsLocal on oc_preferences to know the user's account's type
            \OC_Preferences::setValue($user->getUID(), 'settings', 'IsLocal', TRUE);
        }
    }

    public function preDeleteUser(\OC\User\User $user) {
        $this->fileAction->saveUserFiles($user);
    }

    public function postDeleteUser(\OC\User\User $user) {
        $this->mailAction->mailUserDeletion($user);
    }

    public function register() {
        $myself = $this;

        $this->userManager->listen('\OC\User', 'preDelete', function(\OC\User\User $user) use ($myself) {
            return $this->preDeleteUser($user);
        });

        $this->userManager->listen('\OC\User', 'postDelete', function(\OC\User\User $user) use ($myself) {
            return $this->postDeleteUser($user);
        });

        $this->userManager->listen('\OC\User', 'postCreateUser', function(\OC\User\User $user) use ($myself) {
            return $this->postCreateUser($user);
        });
    }

}
