<?php

namespace Drupal\slack_log;


class SlackMessageFormatter {

public function __construct($message) {

$this->message = $message;
$this->formatted = $this->format($this->message);


}

    /**
     * @param $message
     * @return mixed
     */
    public function format($message)
    {
        return $this;
    }

}