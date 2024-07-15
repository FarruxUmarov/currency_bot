<?php

declare(strict_types=1);

require 'DB.php';

$allusers = new DB();

$allUsersInfo = $allusers->users();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter Bot</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Chat ID</th>
                <th scope="col">Conversion Type</th>
                <th scope="col">Amount</th>
                <th scope="col">Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allUsersInfo as $userInfo): ?>
                <tr>
                    <th scope="row"><?php echo $userInfo['id']; ?></th>
                    <td><?php echo $userInfo['chatId']; ?></td>
                    <td><?php echo $userInfo['state']; ?></td>
                    <td><?php echo $userInfo['amount']; ?></td>
                    <td><?php echo $userInfo['data']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
