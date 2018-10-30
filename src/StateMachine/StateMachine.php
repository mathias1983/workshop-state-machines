<?php
namespace App\StateMachine;
use App\Entity\User;
use App\StateMachine\Step\FinalState;
use App\StateMachine\Step\Mailer;
use App\StateMachine\Step\StateInterface;

class StateMachine implements StateMachineInterface {

    private $state;
    private $mailer;
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user, $mailer) {
        $this->mailer = $mailer;
        $this->user = $user;
    }

    /**
     * @param StateInterface $state The first or current state. Ie the state we should start running now.
     * @return bool True if the job in complete and we do never have to run this state machine for this user again.
     */
    public function start(StateInterface $state): bool {
        $this->state = $state;

        $check = StateInterface::CONTINUE;

        while($check===StateInterface::CONTINUE){
            $check = $this->state->send($this,$this->mailer);

        }

        return $this->state instanceof FinalState;

    }

    public function getUser(): User {
        return $this->user;
    }

    public function setState(StateInterface $state): void {
        $this->state = $state;
    }
}