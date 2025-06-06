<?php

namespace System\Http;

use System\View\View;

class Response
{
    public function __construct(
        private string|View|null $content = '',
        private int              $status = 200,
        private array            $headers = [],
    )
    {
        http_response_code($status);
        // Ajout des en-têtes
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
    }

    /**
     * Retourne une vue HTML ou du JSON selon le contexte
     * Utilise les paramètres nommés PHP 8+ pour plus de clarté
     *
     * @param string $view Nom de la vue
     * @param array $data Données pour la vue
     * @param array $layouts Composants à inclure dans la vue
     * @param array $json Données JSON pour les requêtes AJAX
     * @param int $status Code de statut HTTP
     * @param array $headers En-têtes HTTP
     * @return self
     */
    public static function view(
        string $view,
        array  $data = [],
        array  $layouts = [],
        array  $json = [],
        int    $status = 200,
        array  $headers = []
    ): self
    {

        try {
            $viewInstance = View::make($view, $data);

            // Ajouter les composants s'ils sont fournis
            if (!empty($layouts)) {
                $viewInstance->withlayouts($layouts);
            }

            // Si c'est une requête AJAX ET qu'on a des données JSON
            if (self::isAjaxRequest() && !empty($json)) {
                // Retourner JSON avec le HTML de la vue inclus
                $htmlContent = $viewInstance->render();
                $jsonResponse = array_merge($json, ['html' => $htmlContent]);
                return self::json($jsonResponse, $status, $headers);
            }

            // Sinon, retourner la vue normale
            return new self($viewInstance, $status, $headers);

        } catch (\Exception $e) {
            // Optionnel : journaliser l'erreur
            error_log($e->getMessage());

            // Si c'est AJAX et qu'on a une erreur, retourner JSON
            if (self::isAjaxRequest()) {
                return self::json([
                    'statut' => 'error',
                    'message' => 'Erreur lors du chargement de la vue'
                ], 404, $headers);
            }

            // Sinon renvoyer une vue 404 avec le code 404
            return new self(View::make('errors/404'), status: 404);
        }
    }

    public static function json(array $data, int $status = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';
        return new self(json_encode($data), $status, $headers);
    }

    /**
     * Méthodes de convenance pour les réponses courantes
     */
    public static function success(string $message, array $data = [], string $redirect = null): self
    {
        $response = [
            'statut' => 'succes',
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        if ($redirect) {
            $response['redirect'] = $redirect;
        }

        return self::json($response);
    }

    public static function error(string $message, array $data = [], int $status = 400): self
    {
        $response = [
            'statut' => 'error',
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return self::json($response, $status);
    }

    public static function redirect(string $url, int $status = 302): self
    {
        $headers = ['Location' => $url];
        return new self('', $status, $headers);
    }

    public static function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function send(): void
    {
        if ($this->content instanceof View) {
            echo $this->content->render();
        } else {
            echo $this->content;
        }
    }
}