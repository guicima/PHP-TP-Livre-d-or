<?php
class GuestBook //Manage the messages' file
{
    private $file_dir;

    public function __construct($file)
    {
        $directory = dirname($file);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!file_exists($file)) {
            touch($file);
        }
        $this->file_dir = $file;
    }

    public function addMessage(Message $message): void
    {
        file_put_contents($this->file_dir, $message->toJSON() . PHP_EOL, FILE_APPEND);
    }

    public function getMessages(): array
    {
        return explode(PHP_EOL, trim(file_get_contents($this->file_dir)));
    }
}
