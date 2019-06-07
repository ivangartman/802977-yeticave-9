<?php

require_once 'include/init.php';

$endDate_lot = db_endDate_lot($link);
if ($endDate_lot) {
    foreach ($endDate_lot as $lot) {
        $winnerUser = db_winnerUser($link, $lot['id']);
        foreach ($winnerUser as $user) {
            $sql = $db_add_winner;
            $data = [
                $user['user_id'],
                $lot['id']
            ];
            $res = db_insert($link, $sql, $data);
            if ( ! $res) {
                $error_message = 'Победитель не добавлен';
                $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
            }
            $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
            $transport->setUsername('keks@phpdemo.ru');
            $transport->setPassword('htmlacademy');

            $mailer = new Swift_Mailer($transport);

            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

            $message = new Swift_Message();
            $message->setSubject('Ваша ставка победила!');
            $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
            $message->setBcc([$user['email']]);

            $msg_content = include_template('email.php',
                [
                    'user_name' => $user['name'],
                    'lot_link'  => 'http://802977-yeticave-9/lot.php?page=' . $lot['id'],
                    'lot_name'  => $lot['name'],
                    'my_bets'   => 'http://802977-yeticave-9/my-bets.php'
                ]);
            $message->setBody($msg_content, 'text/html');
            $mailer->send($message);
        }
    }
}
