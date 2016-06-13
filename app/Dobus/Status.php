<?php

namespace App\Dobus;

class Status {
    public function leadCreated()
    {
        return 1;
    }

    public function emailSent()
    {
        return 2;
    }

    public function emailOpened()
    {
        return 3;
    }

    public function emailAnswered()
    {
        return 4;
    }

    public function shouldSendReminderEmail()
    {
        return 5;
    }

    public function reminderEmailSent()
    {
        return 6;
    }

    public function leadDead()
    {
        return 7;
    }
}
