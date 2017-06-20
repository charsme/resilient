<?php

namespace App\Common;

use Slim\Http\Uri as SlimUri;
use Slim\Interfaces\RouterInterface;
use Exception;

/**
* Twig Router Extension for Slim Routing
*/
class Mixer extends \Twig_Extension
{
    protected $config;
    protected $manifest;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getName():string
    {
        return 'slim chars';
    }

    public function getFunctions():array
    {
        return [
            new \Twig_SimpleFunction('asset', array($this, 'assetsPath')),
            new \Twig_SimpleFunction('css', array($this, 'cssAlias')),
            new \Twig_SimpleFunction('js', array($this, 'jsAlias')),
            new \Twig_SimpleFunction('img', array($this, 'imgAlias')),
            new \Twig_SimpleFunction('cdn', array($this, 'getCdnPath'))
        ];
    }

    public function getCdnPath($path = '')
    {
        if ($path && !starts_with($path, '/')) {
            $path = "/{$path}";
        }

        return ($this->config['cdn'] ? : '') . $path;
    }

    public function imgAlias($path)
    {
        return $this->getCdnPath("/img/{$path}");
    }

    public function cssAlias($path = '')
    {
        $path = $path ? "css/{$path}.css" : 'css/app.css';
        return $this->assetsPath($path);
    }

    public function jsAlias($path = '')
    {
        $path = $path ? "js/{$path}.js" : 'js/app.js';
        return $this->assetsPath($path);
    }

    protected function getManifest($manifestDirectory = ''):array
    {
        if ($this->manifest) {
            return $this->manifest;
        }
        
        if (! file_exists($manifestPath = ($manifestDirectory . '/mix-manifest.json'))) {
            throw new Exception('The Mix manifest does not exist.');
        }

        return $this->manifest = json_decode(file_get_contents($manifestPath), true);
    }

    protected function slashMe(string $path):string
    {
        if ($path && ! starts_with($path, '/')) {
            return "/{$path}";
        }

        return $path;
    }

    public function assetsPath($path, $manifestDirectory = ''):string
    {
        $rootPath = getenv('DOCUMENT_ROOT') . '/..';
        $publicFolder = '/public/assets';

        $manifestDirectory = $this->slashMe($manifestDirectory);
        $manifest = $this->getManifest($rootPath . $manifestDirectory);

        $path = $this->slashMe($path);
        $path = $publicFolder . $path;

        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }

        return $this->getCdnPath() . $manifestDirectory . str_replace('/public', '', $manifest[$path]);
    }
}
