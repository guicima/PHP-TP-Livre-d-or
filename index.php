<?php
require 'class' . DIRECTORY_SEPARATOR . 'Message.php';
require 'class' . DIRECTORY_SEPARATOR . 'GuestBook.php';

$empty_post = empty($_POST) ? false : true;
$username = !empty($_POST) ? htmlentities($_POST['username']) : '';
$guest_book = new GuestBook('data' . DIRECTORY_SEPARATOR . 'messages');
$errors = [];
if ($empty_post) {
    $user_message = new Message($_POST['username'], $_POST['message']);
    $valid = $user_message->isValid();
    if ($valid) {
        $guest_book->addMessage($user_message);
        $_POST = [];
    }
    $errors = $user_message->getErrors();
}

require 'elements' . DIRECTORY_SEPARATOR . 'header.php';
?>
<div class="m-5">
    <h1>Livre d'or</h1>
    <form class="w-50" action="" method="POST">
        <?php if ($empty_post) : ?>

            <?php if ($valid) : ?>
                <div class="alert alert-success">Votre message a été correctement envoyé</div>

            <?php else : ?>
                <div class="alert alert-danger">Le formulaire est invalide</div>
            <?php endif; ?>

        <?php endif; ?>
        <div class="form-group">
            <label for="username">Nom</label>
            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" placeholder="Entrez votre pseudonyme" name="username" value="<?= $username ?>">

            <?php if (isset($errors['username'])) : ?>
                <div class="invalid-feedback"><?= $errors['username'] ?></div>
            <?php endif; ?>

        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea type="text" class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" id="message" placeholder="Entrez votre message" name="message"></textarea>

            <?php if (isset($errors['message'])) : ?>
                <div class="invalid-feedback"><?= $errors['message'] ?></div>
            <?php endif; ?>

        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <h3 class="my-2">Vos messages :</h3>
    <div class="card p-3 d-flex flex-column-reverse">

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