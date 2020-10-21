<?php
class Message //Manage the message itself
{
    const LIMIT_USERNAME = 3;
    const LIMIT_MESSAGE = 10;
    private $username;
    private $message;
    private $date;

    public function __construct(string $username, string $message, ?DateTime $date = null)
    {
        $this->username = $username;
        $this->message = $message;
        $this->date = $date != null ? $date : new DateTime();;
    }

    public function isValid(): bool
    {
        return empty($this->getErrors());
    }

    public function getErrors(): array
    {
        $result = [];
        if (strlen($this->username) < self::LIMIT_USERNAME) {
            $result['username'] = "Votre pseudonyme doit contenir au moins " . self::LIMIT_USERNAME . " caractères";
        }
        if (strlen($this->message) < self::LIMIT_MESSAGE) {
            $result['message'] = "Votre message doit contenir au moins " . self::LIMIT_MESSAGE . " caractères";
        }
        return $result;
    }


    public function toHTML(): string //output message into HTML
    {
        $username = htmlentities($this->username);
        $message = nl2br(htmlentities($this->message));
        $this->date->setTimezone(new DateTimeZone('Europe/Luxembourg'));
        return <<<HTML
        <p>
            <strong>$username</strong> <em>le {$this->date->format("d/m/Y à H:i")}</em><br>
            $message
        </p>
HTML;
    }

    public function toJSON(): string //convert object to JSON
    {
        $result = [
            'username' => $this->username,
            'message' => $this->message,
            'date' => $this->date->getTimestamp()
        ];
        return json_encode($result);
    }

    public static function fromJSON(string $string): Message //convert from JSON to instance of Message
    {
        $result = json_decode($string);
        if (!empty($result)) {
            return new self($result->username, $result->message, new DateTime("@{$result->date}"));
        } else {
            die;
        }
    }
}
