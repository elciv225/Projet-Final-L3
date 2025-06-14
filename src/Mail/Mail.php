<?php

namespace System\Mail;

use System\View\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private PHPMailer $mailer;
    private array $config;

    /**
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    /**
     * Configuration par défaut pour MailHog
     */
    private function getDefaultConfig(): array
    {
        return [
            'host' => 'localhost',
            'port' => 1025,
            'username' => '',
            'password' => '',
            'encryption' => '', // Pas de chiffrement pour MailHog
            'from_email' => 'noreply@localhost',
            'from_name' => 'Application'
        ];
    }

    /**
     * Configure PHPMailer pour MailHog
     */
    private function configureMailer(): void
    {
        try {
            // Configuration SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['host'];
            $this->mailer->Port = $this->config['port'];

            // Pas d'authentification pour MailHog par défaut
            if (!empty($this->config['username'])) {
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = $this->config['username'];
                $this->mailer->Password = $this->config['password'];
            } else {
                $this->mailer->SMTPAuth = false;
            }

            // Chiffrement (généralement aucun pour MailHog)
            if (!empty($this->config['encryption'])) {
                $this->mailer->SMTPSecure = $this->config['encryption'];
            }

            // Configuration de l'expéditeur par défaut
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);

            // Configuration pour le développement
            $this->mailer->SMTPDebug = SMTP::DEBUG_OFF; // Changez en DEBUG_SERVER pour debug
            $this->mailer->isHTML(true);

        } catch (Exception $e) {
            throw new \Exception("Erreur de configuration de l'email: " . $e->getMessage());
        }
    }

    /**
     * Définir l'expéditeur
     */
    public function from(string $email, string $name = ''): self
    {
        try {
            $this->mailer->setFrom($email, $name);
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de la définition de l'expéditeur: " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Ajouter un destinataire
     */
    public function to(string $email, string $name = ''): self
    {
        try {
            $this->mailer->addAddress($email, $name);
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de l'ajout du destinataire: " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Ajouter un destinataire en copie
     */
    public function cc(string $email, string $name = ''): self
    {
        try {
            $this->mailer->addCC($email, $name);
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de l'ajout de la copie: " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Ajouter un destinataire en copie cachée
     */
    public function bcc(string $email, string $name = ''): self
    {
        try {
            $this->mailer->addBCC($email, $name);
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de l'ajout de la copie cachée: " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Définir le sujet
     */
    public function subject(string $subject): self
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    /**
     * Définir le contenu HTML via une vue
     */
    public function view(string $viewName, array $data = [], array $layouts = []): self
    {
        try {
            $view = View::make($viewName, $data);

            if (!empty($layouts)) {
                $view->withlayouts($layouts);
            }

            $htmlContent = $view->render();
            $this->mailer->Body = $htmlContent;

            // Générer automatiquement une version texte simple
            $this->mailer->AltBody = $this->htmlToText($htmlContent);

        } catch (\Exception $e) {
            throw new \Exception("Erreur lors du rendu de la vue: " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Définir le contenu HTML directement
     */
    public function html(string $html): self
    {
        $this->mailer->Body = $html;
        $this->mailer->AltBody = $this->htmlToText($html);
        return $this;
    }

    /**
     * Définir le contenu texte
     */
    public function text(string $text): self
    {
        $this->mailer->AltBody = $text;
        return $this;
    }

    /**
     * Ajouter une pièce jointe
     */
    public function attach(string $path, string $name = ''): self
    {
        try {
            $this->mailer->addAttachment($path, $name);
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de l'ajout de la pièce jointe: " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Envoyer l'email
     */
    public function send(): bool
    {
        try {
            $result = $this->mailer->send();
            $this->reset(); // Nettoyer pour la prochaine utilisation
            return $result;
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de l'envoi de l'email: " . $e->getMessage());
        }
    }

    /**
     * Réinitialiser le mailer pour un nouvel email
     */
    private function reset(): void
    {
        $this->mailer->clearAddresses();
        $this->mailer->clearAllRecipients();
        $this->mailer->clearAttachments();
        $this->mailer->clearCustomHeaders();
        $this->mailer->clearReplyTos();
        $this->mailer->Subject = '';
        $this->mailer->Body = '';
        $this->mailer->AltBody = '';
    }

    /**
     * Convertir HTML en texte simple
     */
    private function htmlToText(string $html): string
    {
        // Conversion simple HTML vers texte
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Méthode statique pour créer une nouvelle instance
     */
    public static function make(array $config = []): self
    {
        return new self($config);
    }

    /**
     * Méthode de convenance pour envoyer rapidement un email avec vue
     */
    public static function sendView(string $to, string $subject, string $view, array $data = [], array $layouts = []): bool
    {
        return self::make()
            ->to($to)
            ->subject($subject)
            ->view($view, $data, $layouts)
            ->send();
    }

    /**
     * Méthode de convenance pour envoyer rapidement un email HTML
     */
    public static function sendHtml(string $to, string $subject, string $html): bool
    {
        return self::make()
            ->to($to)
            ->subject($subject)
            ->html($html)
            ->send();
    }

    /**
     * Méthode de convenance pour envoyer rapidement un email texte
     */
    public static function sendText(string $to, string $subject, string $text): bool
    {
        return self::make()
            ->to($to)
            ->subject($subject)
            ->text($text)
            ->send();
    }
}