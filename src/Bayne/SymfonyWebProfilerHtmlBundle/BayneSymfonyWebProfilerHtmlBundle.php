<?php

namespace Bayne\SymfonyWebProfilerHtmlBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;

class BayneSymfonyWebProfilerHtmlBundle extends Bundle
{
    public function getParent()
    {
        return 'WebProfilerBundle';
    }

}
