<?php
namespace OCA\User_Account_Actions\Hooks;

class UserHooks {

    protected $userManager;
    protected $mailAction;

    public function __construct($userManager, $mailAction){
        $this->userManager = $userManager;
        $this->mailAction = $mailAction;

    }

    public function postCreateUser(\OC\User\User $user) {
        $this->mailAction->mailUserCreation($user);
    }

    public function preDeleteUser(\OC\User\User $user) {
        $this->fileAction->saveUserFiles($user);
    }

    public function postDeleteUser(\OC\User\User $user) {
        $this->mailAction->mailUserDeletion($user);
    }

    public function register() {
        $myself = $this;

        $this->userManager->listen('\OC\User', 'predelete', function(\OC\User\User $user) use ($myself) {
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