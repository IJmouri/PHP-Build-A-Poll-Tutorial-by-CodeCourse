<?php

require 'app/db.php';

if (!isset($_GET['poll'])) {
    header('Location: index.php');
} else {
    $id = $_GET['poll'];

    // get general poll information
    $pollQuery = $db->prepare("
        SELECT id,question
        FROM polls
        WHERE id = :poll
        AND DATE(NOW()) BETWEEN start_date AND end_date
    ");

    $pollQuery->execute([
        'poll' => $id
    ]);

    $poll = $pollQuery->fetchObject();
    //    print_r($poll);
    // get choices

    $choicesQuery = $db->prepare("
        SELECT polls.id, polls_choices.id AS choice_id, polls_choices.name
        FROM polls
        JOIN polls_choices
        ON polls.id = polls_choices.poll
        WHERE polls.id = :poll
        AND DATE(NOW()) BETWEEN polls.start_date AND polls.end_date
    ");

    $choicesQuery->execute([
        'poll' => $id
    ]);

    // print_r($choicesQuery->fetchObject());
    while ($row = $choicesQuery->fetchObject()) {
        $choices[] = $row;
    }
    //  print_r($choices);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <?php if (!$poll) : ?>
        <p>That poll does not exist</p>
    <?php else : ?>
        <div class="poll">
            <div class="poll-question">
                <?php echo $poll->question; ?>
            </div>
            <?php if (!empty($choices)) : ?>
                <form action="vote.php" method="post">
                    <div class="poll-options">
                        <?php foreach($choices as $index => $choice): ?>
                        
                        <div class="poll-option">
                            <input type="radio" name="choice" value="<?php echo $choice->choice_id ?>" id="c<?php echo $index ?>">
                            <label for="c<?php echo $index ?>"><?php echo $choice -> name ?></label>
                        </div>

                        <?php endforeach; ?>
                    </div>
                    <input type="submit" value="Submit Answer">
                    <input type="hidden" name="poll" value="1">
                </form>
            <?php else: ?>
                <p> There are no choices right now</p>
            <?php endif; ?>
        </div>
    <?php endif ?>
</body>

</html>