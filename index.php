<?php
require 'class' . DIRECTORY_SEPARATOR . 'Message.php';
require 'class' . DIRECTORY_SEPARATOR . 'GuestBook.php';

$empty_post = empty($_POST) ? false : true;
$username = !empty($_POST) ? $_POST['username'] : '';
$date = new DateTime;
$guest_book = new GuestBook('data' . DIRECTORY_SEPARATOR . 'messages');
//$test2 = Message::fromJSON($json);
if ($empty_post) {
    $user_message = new Message($_POST['username'], $_POST['message'], $date);
    if ($user_message->isValid()) {
        $guest_book->addMessage($user_message);
    }
    unset($_POST);
}

require 'elements' . DIRECTORY_SEPARATOR . 'header.php';
?>
<div class="m-5">
    <form class="w-50" action="" method="POST">
        <?php if ($empty_post) : ?>

            <?php if ($user_message->isValid()) : ?>
                <div class="alert alert-success">
                    <p>Votre message a été correctement envoyé</p>
                </div>

            <?php else : ?>
                <?php foreach ($user_message->getErrors() as $error) : ?>
                    <div class="alert alert-danger">
                        <p><?= $error ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php endif; ?>
        <div class="form-group">
            <label for="username">Nom</label>
            <input type="text" class="form-control" id="username" placeholder="Entrez votre pseudonyme" minlength="0" name="username" value="<?= $username ?>">
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <input type="text" class="form-control" id="message" placeholder="Entrez votre message" minlength="0" name="message">
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <div class="card p-3 d-flex flex-column-reverse mt-2">
        <?php foreach ($guest_book->getMessages() as $data_message) {
            $message = Message::fromJSON($data_message);
            echo $message->toHTML();
        }
        ?>
    </div>
</div>
<?php
require 'elements' . DIRECTORY_SEPARATOR . 'footer.php';
?>