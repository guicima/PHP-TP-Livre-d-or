<?php
class GuestBook //Manage the messages' file
{
    private $file_dir;

    public function __construct($file_dir)
    {
        $this->file_dir = $file_dir;
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
