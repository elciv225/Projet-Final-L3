<?php

namespace App\Controllers;

use System\Http\Response;

class AccueilController
{
    public function index() :Response
    {
        return new Response("Hello from Kernel!");
    }
}