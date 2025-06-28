<?php

namespace App\Models;

class MenuTraitement
{
    protected string $table = 'menu_traitement';
    private Menu $menu;
    private Traitement $traitement;

    public function __construct(Menu $menu, Traitement $traitement)
    {
        $this->menu = $menu;
        $this->traitement = $traitement;
    }


}