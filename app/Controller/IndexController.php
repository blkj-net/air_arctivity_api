<?php

declare(strict_types=1);

namespace App\Controller;

class IndexController extends AbstractController
{
    public function index()
    {
        $method = $this->request->getMethod();
        return [
            'method'  => $method,
            'message' => "Hello hyperf.s2ss",
        ];
    }
}
