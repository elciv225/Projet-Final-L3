<?php

namespace System\View;

class View
{
    private string $view;
    private array $data;
    private array $layouts = [];

    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function withlayouts(array $layouts): self
    {
        $this->layouts = $layouts;
        return $this;
    }

    public function render(): string
    {
        // Extraction des données pour qu'elles soient disponibles dans la vue
        extract($this->data);

        // Buffer pour le contenu final
        $finalContent = '';

        // Inclusion des composants
        if ($this->layouts) {
            foreach ($this->layouts as $layout) {
                // Démarrage d'un tampon
                ob_start();

                $layoutPath = BASE_PATH . '/views/layouts/' . $layout . '.php';

                if (!file_exists($layoutPath)) {
                    throw new \Exception("Le composant {$layout} n'existe pas");
                }

                require_once $layoutPath;

                // Ajout du composant au contenu final
                $finalContent .= ob_get_clean();
            }
        }

        // Inclusion du fichier de vue principal
        ob_start();

        $viewPath = BASE_PATH . '/views/' . $this->view . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("La vue {$this->view} n'existe pas");
        }

        require $viewPath;

        // Ajout du contenu principal
        $finalContent .= ob_get_clean();

        return $finalContent;
    }

    public static function make(string $view, array $data = []): self
    {
        return new self($view, $data);
    }
}