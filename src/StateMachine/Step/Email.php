<?php
namespace App\StateMachine\Step;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

class Email implements StateInterface {

    /**
     * @return int To communicate back to the state machine if we should self::STOP running
     *             or if we should self::CONTINUE with the next state.
     */
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int {
        $user = $stateMachine->getUser();

        if(empty($user->getEmail())){
            $mailer->sendEmail($user,"Please add your email");

        }
        $stateMachine->setState(new Twitter());
        return self::CONTINUE;
    }
}