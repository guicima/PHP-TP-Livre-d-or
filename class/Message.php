<?php
class Message //Manage the message itself
{
    private $username;
    private $message;
    private $date;

    public function __construct(string $username, string $message, DateTime $date = null)
    {
        $this->username = $username;
        $this->message = $message;
        if ($date != null) {
            $this->date = $date;
        } else {
            $this->date = new DateTime();
            $this->date->setTimezone(new DateTimeZone('Europe/Luxembourg'));
        }
    }

    public function isValid(): bool
    {
        if (strlen($this->username) >= 3 && strlen($this->message) >= 10) {
            return true;
        } else {
            return false;
        }
    }

    public function getErrors(): array
    {
        if (!$this->isValid() && strlen($this->username) < 3) {
            $result['username'] = "Votre pseudonyme doit contenir au moins 3 caractères";
        }
        if (!$this->isValid() && strlen($this->message) < 10) {
            $result['message'] = "Votre message doit contenir au moins 10 caractères";
        }
        return $result;
    }


    public function toHTML(): string //output message into HTML
    {
        return <<<HTML
        <p>
            <strong>{$this->username}</strong> <em>le {$this->date->format("d/m/Y à H:i")}</em><br>
            {$this->message}
        </p>
HTML;
    }

    public function toJSON(): string //convert object to JSON
    {
        $result = [
            'username' => $this->username,
            'message' => $this->message,
            'date' => $this->date->format('U')
        ];
        return json_encode($result);
    }

    public static function fromJSON(string $string): Message //convert from JSON to instance of Message
    {
        $result = json_decode($string);
        if (!empty($result)) {
            $time = new DateTime("@{$result->date}");
            $time->setTimezone(new DateTimeZone('Europe/Luxembourg'));
            return new Message($result->username, $result->message, $time);
        } else {
            die;
        }
    }
}
