<?php
class Message
{
    private $username;
    private $message;
    private $date;

    public function __construct(string $username, string $message, DateTime $date)
    {
        $this->username = $username;
        $this->message = $message;
        $this->date = $date;
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


    public function toHTML(): string
    {
        return <<<HTML
        <p>
            <strong>{$this->username}</strong> <em>{$this->date->format('Y-m-d H:i')}</em><br>
            {$this->message}
        </p>
HTML;
    }

    public function toJSON(): string
    {
        $result = [
            'username' => $this->username,
            'message' => $this->message,
            'date' => $this->date->format('U')
        ];
        return json_encode($result);
    }

    public static function fromJSON(string $string): Message
    {
        $result = json_decode($string);
        if (!empty($result)) {
            $time = new DateTime("@{$result->date}");
            return new Message($result->username, $result->message, $time);
        } else {
            die;
        }
    }
}
