<?php
require_once __DIR__ . '/services/MailService.php';

if (MailService::sendWelcome('lanoulouna24@gmail.com', 'Test SPARKMIND')) {
    echo "OK : mail envoyé";
} else {
    echo "ERREUR : envoi impossible";
}
