<?php

declare(strict_types=1);

namespace App;

use App\Service\Database;
use App\Service\MailerService;
use App\StateMachine\StateMachine;
use App\StateMachine\Step\Mailer;

class Worker
{
    private $db;
    private $mailer;

    public function __construct(Database $em, MailerService $mailer)
    {
        $this->db = $em;
        $this->mailer = $mailer;
    }

    public function run()
    {
        $users = $this->db->getAllUsers();



        foreach ($users as $user) {
            // TODO Create a StateMachine object and call ->start()
            $stateMachine = new StateMachine($user,$this->mailer);
            $stateMachine->start(new Mailer());
        }

        $this->db->saveUsers($users);
    }
}
