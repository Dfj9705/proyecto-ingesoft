<?php

namespace Classes;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;
use Exception;

class Email
{
    public $mail = null;
    public function __construct($from = null, $name = null)
    {
        if (!isset($_ENV['EMAIL_HOST']) || !isset($_ENV['EMAIL_USERNAME']) || !isset($_ENV['EMAIL_PASSWORD']) || !isset($_ENV['EMAIL_PORT']) || !isset($_ENV['EMAIL_FROM_ADDRESS'])) {
            throw new Exception('Las variables de entorno para la configuración del correo no están definidas.');
        }
        $this->mail = new PHPMailer(true);
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );


        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['EMAIL_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['EMAIL_USERNAME'];
        $this->mail->Password = $_ENV['EMAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = $_ENV['EMAIL_PORT'];
        $this->mail->CharSet = "UTF-8";
        $this->mail->AddReplyTo($from ?? $_ENV['EMAIL_FROM_ADDRESS'], $name ?? $_ENV['EMAIL_FROM_ADDRESS']);
        $this->mail->setFrom($from ?? $_ENV['EMAIL_FROM_ADDRESS'], $name ?? $_ENV['EMAIL_FROM_ADDRESS']);
        $imagePath = __DIR__ . '/../public/images/scrum.png';
        $this->mail->AddEmbeddedImage($imagePath, 'escudo', 'scrum.png');

        $this->mail->isHTML(true);
    }

    public function generateEmail($subject = '', array $addresses = [], $body = '')
    {
        $this->mail->Subject = $subject;
        foreach ($addresses as $key => $address) {
            $this->mail->addAddress($address);
        }
        $this->mail->Body = $body;

        return $this->mail;
    }
}
