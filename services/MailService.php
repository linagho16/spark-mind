<?php
// ============================================
// CONFIGURATION : PHPMailer sans Composer
// ============================================

require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';
require_once __DIR__ . '/../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService {

    // 🔐 CONFIG SMTP (Gmail + mot de passe d’application)
    private static $smtpHost     = 'smtp.gmail.com';
    private static $smtpUsername = 'lanoulouna24@gmail.com';      // adresse d’envoi
    private static $smtpPassword = 'bgwrpzjbxqlocqxo'; // <-- mets ici ton mot de passe d’application Gmail
    private static $fromEmail    = 'lanoulouna24@gmail.com';      // adresse "From"
    private static $fromName     = 'sparkmind';

    /**
     * Email de bienvenue (après inscription)
     */
    public static function sendWelcome(string $toEmail, string $userName): bool
    {
        $subject = "Bienvenue sur SPARKMIND ✨";

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style=\"
                background: linear-gradient(135deg, #1A464F, #0f2730);
                padding: 30px;
                text-align: center;\">
                <h1 style='color: white; margin: 0;'>Bienvenue sur SPARKMIND</h1>
                <p style='color:#FBEEDD;margin-top:8px;'>Quand la pensée devient espoir</p>
            </div>
            <div style='padding: 30px; background: #f9f3ea;'>
                <p>Bonjour <strong>$userName</strong>,</p>
                <p>Votre compte <strong>SPARKMIND</strong> a été créé avec succès 🌟.</p>
                <p>Vous pouvez maintenant :</p>
                <ul>
                    <li>Demander de l'aide ou proposer votre aide</li>
                    <li>Exprimer ce que vous ressentez dans un espace bienveillant</li>
                    <li>Rejoindre une communauté qui écoute et accompagne</li>
                </ul>
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='http://localhost/sparkmind_mvc_100percent/index.php?page=login'
                       style=\"
                           background: #1A464F;
                           color: white;
                           padding: 12px 30px;
                           text-decoration: none;
                           border-radius: 5px;
                           display: inline-block;\">
                        Se connecter à SPARKMIND
                    </a>
                </p>
            </div>
            <div style='padding: 20px; text-align: center; color: #666; font-size: 12px; background:#FBEDD7;'>
                © 2024 SPARKMIND - Tous droits réservés
            </div>
        </div>";

        return self::send($toEmail, $subject, $body);
    }

    /**
     * Email de confirmation de changement de mot de passe
     */
    public static function sendPasswordChanged(string $toEmail, string $userName): bool
    {
        $subject = "Votre mot de passe SPARKMIND a été modifié";
        $date    = date('d/m/Y à H:i');

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style=\"
                background: linear-gradient(135deg, #1A464F, #0f2730);
                padding: 30px;
                text-align: center;\">
                <h1 style='color: white; margin: 0;'>Mot de passe modifié</h1>
            </div>
            <div style='padding: 30px; background: #f9f3ea;'>
                <p>Bonjour <strong>$userName</strong>,</p>
                <p>Votre mot de passe <strong>SPARKMIND</strong> a été modifié avec succès le <strong>$date</strong>.</p>
                <div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;'>
                    <strong>Ce n'était pas vous ?</strong><br>
                    Si vous n'êtes pas à l'origine de cette modification, contactez-nous immédiatement.
                </div>
            </div>
            <div style='padding: 20px; text-align: center; color: #666; font-size: 12px; background:#FBEDD7;'>
                © 2024 SPARKMIND - Tous droits réservés
            </div>
        </div>";

        return self::send($toEmail, $subject, $body);
    }

    /**
     * Email de notification de compte bloqué
     */
    public static function sendAccountBlocked(string $toEmail, string $userName): bool
    {
        $subject = "Votre compte SPARKMIND a été suspendu";

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>

            <!-- Bandeau haut avec logo -->
            <div style=\"text-align:center;
                        padding:25px;
                        background:linear-gradient(135deg,#ca7200,#c47600);
                        border-radius:8px 8px 0 0;\">
                <img src='https://i.postimg.cc/3NHKx7sD/Gemini-Generated-Image-33cenv33cenv33ce-removebg-preview.png'
                    alt='SPARKMIND Logo'
                    style='width:80px;height:80px;border-radius:50%;margin-bottom:10px;'>
                <h2 style='color:white;margin:10px 0 0;font-size:26px;'>
                    ⚠️ Compte suspendu
                </h2>
            </div>

            <!-- Corps du mail -->
            <div style='padding: 30px; background: #FFF7EF;'>
                <p>Bonjour <strong>$userName</strong>,</p>

                <p>
                    Votre compte <strong>SPARKMIND</strong> a été
                    <strong>temporairement suspendu</strong> par un administrateur.
                </p>

                <p>
                    Cela peut être lié à une activité non conforme aux règles de la communauté
                    ou à une vérification de sécurité.
                </p>

                <p>
                    Si vous pensez qu'il s'agit d'une erreur, vous pouvez répondre à cet e-mail
                    ou contacter l’équipe SPARKMIND.
                </p>
            </div>

            <!-- Pied de page -->
            <div style='padding: 12px;
                        text-align: center;
                        color: #333;
                        font-size: 12px;
                        background:#F5DCC2;
                        border-radius:0 0 8px 8px;'>
                © 2024 SPARKMIND – Quand la pensée devient espoir
            </div>

        </div>";

        return self::send($toEmail, $subject, $body);
    }


    /**
     * Email de notification de compte débloqué / réactivé
     */
    public static function sendAccountUnblocked(string $toEmail, string $userName): bool
    {
        $subject = "Votre compte SPARKMIND a été réactivé";

        $body = '
        <div style="text-align:center;padding:25px;background:#1A464F;border-radius:8px 8px 0 0;">
            <img src="https://i.postimg.cc/3NHKx7sD/Gemini-Generated-Image-33cenv33cenv33ce-removebg-preview.png"
                alt="SPARKMIND Logo"
                style="width:80px;height:80px;border-radius:50%;margin-bottom:10px;">
            <h2 style="color:white;margin:10px 0 0;font-family:Poppins,Arial,sans-serif;font-size:28px;">
                ✨ Votre compte est réactivé !
            </h2>
        </div>

        <div style="background:#FFF7EF;padding:30px;font-family:Poppins,Arial,sans-serif;">
            <p>Bonjour <strong>' . htmlspecialchars($userName) . '</strong>,</p>

            <p>
                Très bonne nouvelle ! Votre compte <strong>SPARKMIND</strong> a bien été
                <strong>réactivé</strong> 🎉  
                Vous pouvez maintenant vous reconnecter et reprendre votre activité au sein de la communauté.
            </p>

            <div style="text-align:center;margin:25px 0;">
                <a href="https://sparkmind.com/login"
                style="background:#1A464F;color:white;padding:12px 24px;border-radius:30px;
                        text-decoration:none;font-size:15px;display:inline-block;">
                    🌟 Se connecter à SPARKMIND
                </a>
            </div>

            <p>
                Heureux de vous revoir parmi nous 💛<br>
                L’équipe SPARKMIND reste toujours à vos côtés.
            </p>
        </div>

        <div style="background:#F5DCC2;padding:12px;text-align:center;font-size:12px;color:#333;
                    border-radius:0 0 8px 8px;">
            © SPARKMIND – Quand la pensée devient espoir
        </div>
        ';

        return self::send($toEmail, $subject, $body);
    }
    public static function sendPasswordResetCode(string $toEmail, string $userName, string $code): bool
    {
        $subject = "Code de vérification pour réinitialiser votre mot de passe";

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>

            <!-- Bandeau haut -->
            <div style='text-align:center;padding:25px;background:#1A464F;border-radius:8px 8px 0 0;'>
                <img src='https://i.postimg.cc/3NHKx7sD/Gemini-Generated-Image-33cenv33cenv33ce-removebg-preview.png'
                    alt='SPARKMIND Logo'
                    style='width:70px;height:70px;border-radius:50%;margin-bottom:10px;'>
                <h2 style='color:#FBEEDD;margin:0;font-size:24px;'>
                    🔐 Code de vérification
                </h2>
            </div>

            <!-- Corps -->
            <div style='padding: 25px; background: #FFF7EF;'>
                <p>Bonjour <strong>$userName</strong>,</p>

                <p>
                    Vous avez demandé à réinitialiser votre mot de passe
                    <strong>SPARKMIND</strong>. Voici votre code de vérification :
                </p>

                <p style='text-align:center;margin:25px 0;'>
                    <span style='display:inline-block;
                                padding:10px 24px;
                                border-radius:6px;
                                background:#1A464F;
                                color:#fff;
                                font-size:20px;
                                letter-spacing:4px;'>
                        $code
                    </span>
                </p>

                <p style='font-size:14px;color:#555;'>
                    Ce code est valable pendant <strong>15 minutes</strong>.<br>
                    Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet e-mail.
                </p>

                <p style='text-align:center;margin-top:20px;'>
                    <a href='http://localhost/sparkmind_mvc_100percent/index.php?page=reset_password'
                    style='background:#1A464F;
                            color:#fff;
                            padding:10px 22px;
                            border-radius:24px;
                            text-decoration:none;
                            font-size:14px;
                            display:inline-block;'>
                        ✨ Saisir mon code
                    </a>
                </p>
            </div>

            <!-- Pied -->
            <div style='background:#F5DCC2;
                        padding:12px;
                        text-align:center;
                        font-size:12px;
                        color:#333;
                        border-radius:0 0 8px 8px;'>
                © 2024 SPARKMIND – Quand la pensée devient espoir
            </div>

        </div>";

        return self::send($toEmail, $subject, $body);
    }



    /**
     * Méthode principale d'envoi d'email (PHPMailer)
     */
    private static function send(string $toEmail, string $subject, string $body): bool
    {
        $mail = new PHPMailer(true);

        try {
            // DEBUG (tu pourras mettre 0 quand ça marchera bien)
            $mail->SMTPDebug = 0;

            // Config SMTP
            $mail->isSMTP();
            $mail->Host       = self::$smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = self::$smtpUsername;
            $mail->Password   = self::$smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // ⚠ Désactiver la vérification SSL (OK pour tests en local XAMPP)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Destinataires
            $mail->setFrom(self::$fromEmail, self::$fromName);
            $mail->addAddress($toEmail);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));

            $mail->send();
            return true;

        } catch (Exception $e) {
            echo "<pre>";
            echo "Mailer Error : " . $mail->ErrorInfo . "\n";
            echo "</pre>";
            return false;
        }
    }
}
