<?php

namespace System\View;

class View
{
    private string $view;
    private array $data;
    private array $components = [];

    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function withComponents(array $components): self
    {
        $this->components = $components;
        return $this;
    }

    public function render(): string
    {
        // Extraction des données pour qu'elles soient disponibles dans la vue
        extract($this->data);

        // Buffer pour le contenu final
        $finalContent = '';

        // Inclusion des composants
        if ($this->components) {
            foreach ($this->components as $component) {
                // Démarrage d'un tampon
                ob_start();

                $componentPath = BASE_PATH . '/views/components/' . $component . '.php';

                if (!file_exists($componentPath)) {
                    throw new \Exception("Le composant {$component} n'existe pas");
                }

                require $componentPath;

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