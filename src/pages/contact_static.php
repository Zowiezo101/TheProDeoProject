<?php 
/**
 * There are three possible situations for this page:
 * 1. Feedback has successfully been sent and a success message is shown
 * 2. Sending feedback has failed and the error message is shown
 * 3. No feedback has been sent, show the feedback form
 */
$sent = isset($_SESSION["sent"]) ? $_SESSION["sent"] : false;
$error = isset($_SESSION["error"]) ? $_SESSION["error"] : false;
?>

<div class="container-fluid">
    <div class="row">
        <div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4"><?= $dict["navigation.contact_us"]; ?></h2>
            <p><?= $dict["contact.overview"]; ?></p>
            <p class="mb-0 lead">
                <a href="mailto:prodeoproductions2u@gmail.com" target="_blank">ProDeoProductions2U@gmail.com</a>
            </p>
        </div>

    <?php if ($sent != false) { ?>
        <!-- Feedback has successfully been sent and a success message is shown -->
        <div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4"><?= $dict["contact.sent_title"]; ?></h2>
            <p><?= $dict["contact.sent_message"]; ?></p>
        </div>

        <?php 
        unset($_SESSION["sent"]);
        unset( $_SESSION["error"]);
        ?>
    <?php } else if ($error != false) { ?>
        <!-- Sending feedback has failed and the error message is shown -->
        <div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4"><?= $dict["contact.error_title"]; ?></h2>
            <p><?= $dict["contact.error_message"].$error; ?></p>
        </div>

        <?php 
        unset($_SESSION["sent"]);
        unset( $_SESSION["error"]);
        ?>
    <?php } else { ?>
        <!-- No feedback has been sent, show the feedback form -->
        <div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4"><?= $dict["contact.form"]; ?></h2>
            <form method="post" action="src/tools/send_feedback.php">
                <div class="form-group"> <input type="text" class="form-control" name="name" placeholder="<?= $dict["contact.name"]; ?>"> </div>
                <div class="form-group"> <input type="text" class="form-control" name="subject" required placeholder="<?= $dict["contact.subject"]; ?>"> </div>
                <div class="form-group"> <textarea class="form-control" name="message" rows="3" required placeholder="<?= $dict["contact.message"]; ?>"></textarea> </div>
                <button type="submit" class="btn btn-primary" name="send_feedback"><?= $dict["contact.send"]; ?></button>
            </form>
        </div>
    <?php } ?>
    </div>
</div>





