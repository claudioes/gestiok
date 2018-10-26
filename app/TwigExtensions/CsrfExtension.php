<?php

namespace App\TwigExtensions;

use Slim\Csrf\Guard;

class CsrfExtension extends \Twig_Extension
{
    /**
     * @var Slim\Csrf\Guard
     */
    private $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;

    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('csrf_field', [$this, 'csrfField'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('csrf_name', [$this, 'csrfValue']),
            new \Twig_SimpleFunction('csrf_value', [$this, 'csrfName']),
        ];
    }

    public function csrfField(): string
    {
        return '
            <input type="hidden" name="'. $this->guard->getTokenNameKey() .'" value="'. $this->guard->getTokenName() .'">
            <input type="hidden" name="'. $this->guard->getTokenValueKey() .'" value="'. $this->guard->getTokenValue() .'">
        ';
    }

    public function csrfName(): string
    {
        return $this->guard->getTokenName();
    }

    public function csrfValue(): string
    {
        return $this->guard->getTokenValue();
    }
}
