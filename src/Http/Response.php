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

    public function send(): void
    {
        if ($this->content instanceof View) {
            echo $this->content->render();
        } else {
            echo $this->content;
        }
    }

    public static function view(string $view, array $data = [], ?array $components = [], int $status = 200, array $headers = []): self
    {
        try {
            $viewInstance = View::make($view, $data);

            if ($components) {
                $viewInstance->withComponents($components);
            }

            return new self($viewInstance, $status, $headers);
        } catch (\Exception $e) {
            // Optionnel : journaliser l'erreur
            error_log($e->getMessage());

            // Renvoyer une vue 404 avec le code 404
            return new self(View::make('errors/404'), 404);
        }
    }

    public static function json(array $data, int $status = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';
        return new self(json_encode($data), $status, $headers);
    }


}