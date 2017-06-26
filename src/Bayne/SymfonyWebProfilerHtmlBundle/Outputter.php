<?php

namespace Bayne\SymfonyWebProfilerHtmlBundle;


use Symfony\Bundle\WebProfilerBundle\Profiler\TemplateManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class Outputter
{
    /**
     * @var Profiler
     */
    private $profiler;
    /**
     * @var array
     */
    private $templates;
    /**
     * @var TemplateManager
     */
    private $templateManager;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(
        Profiler $profiler,
        \Twig_Environment $twig,
        $templates
    ) {
        $this->profiler = $profiler;
        $this->templates = $templates;
        $this->twig = $twig;
    }

    protected function getTemplateManager()
    {
        if (null === $this->templateManager) {
            $this->templateManager = new TemplateManager($this->profiler, $this->twig, $this->templates);
        }

        return $this->templateManager;
    }

    public function write($token, $outputDirectory)
    {
        $request = new Request();
        $profile = $this->profiler->loadProfile($token);
        if (null === $profile) {
            return;
        }
        $page = 'home';
        $templates = array_filter($this->templates, function ($template) {
            return $template[0] !== 'router';
        });
        $panels = array_map(function ($panel) {
            return $panel[0];
        }, $templates);
        \SqlFormatter::$cli = false;
        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }
        foreach ($panels as $panel) {
            $templateName = $this->getTemplateManager()->getName($profile, $panel);
            $templateVariables = array(
                'static' => true,
                'token' => $token,
                'app' => [
                    'request' => $request
                ],
                'profile' => $profile,
                'collector' => $profile->getCollector($panel),
                'panel' => $panel,
                'page' => $page,
                'request' => $request,
                'templates' => $this->getTemplateManager()->getNames($profile),
                'is_ajax' => false,
                'profiler_markup_version' => 2, // 1 = original profiler, 2 = Symfony 2.8+ profiler
            );
            try {
                $content = $this->twig->render(
                    $templateName,
                    $templateVariables
                );
                file_put_contents($outputDirectory.'/'.$panel.'.html', $content);
            } catch(\Twig_Error $e) {
                echo $panel."\n";
                echo $e->getMessage()."\n";
            }

        }
    }
}
