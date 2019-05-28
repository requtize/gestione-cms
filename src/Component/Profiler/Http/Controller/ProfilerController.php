<?php

namespace Gestione\Component\Profiler\Http\Controller;

use Symfony\Component\HttpFoundation\Request;
use Gestione\Framework\Controller\AbstractController;

class ProfilerController extends AbstractController
{
    public function homepage(Request $request, $token)
    {
        $profile = $this->get('profiler')->loadProfile($token);

        if(! $profile)
        {
            return $this->render('@profiler/profile-not-found.tpl', [
                'token' => $token
            ]);
        }

        return $this->render('@profiler/homepage.tpl', [
            'profile' => $profile
        ]);
    }
}
