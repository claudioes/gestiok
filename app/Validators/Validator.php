<?php

namespace App\Validators;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Translation\Translator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Slim\Http\Request;
use App\Validators\Constraints as MyAssert;

abstract class Validator
{
    /**
     * Input
     * @var array
     */
    protected $input;

    /**
     * Arguments
     * @var array
     */
    protected $args;

    /**
     * Errors
     * @var array
     */
    protected $errors = [];

    /**
     * Translator
     * @var \Symfony\Component\Translation\Translator
     */
    protected $translator;

    protected abstract function getRules(): array;

    public function __construct(array $input, array $args = [])
    {
        $this->input = $input;
        $this->args = $args;
    }

    /**
     * Make
     * 
     * @param  \Slim\Http\Request $request
     * @param  \Symfony\Component\Translation\Translator $translator
     * 
     * @return \App\Validators\Validator
     */
    public static function make(Request $request, Translator $translator = null)
    {
        $input = $request->getParams();
        
        foreach($request->getAttributes() as $key => $value) {
            if (is_string($value) && ! array_key_exists($key, $input)) {
                $input[$key] = $value;
            }
        }

        $validator = new static($input);

        if ($translator) {
            $validator->setTranslator($translator);
        }

        return $validator;
    }

    /**
     * Validate
     * 
     * @return App\Validators\Validator This
     */
    public function validate()
    {
        $constraint = new Assert\Collection([
            'fields' => $this->getRules(),
            'allowExtraFields' => true,
        ]);
        $builder = Validation::createValidatorBuilder();

        if ($this->translator) {
            $builder->setTranslator($this->translator)->setTranslationDomain('validators');
        }

        $validator = $builder->getValidator();
        $violations = $validator->validate($this->input, $constraint);

        $this->errors = [];

        if (count($violations)) {
            $accessor = PropertyAccess::createPropertyAccessor();

            foreach($violations as $violation) {
                $accessor->setValue($this->errors, $violation->getPropertyPath(), $violation->getMessage());
            }
        }

        return $this;
    }

    /**
     * Performs the validation and return if it failed
     * 
     * @return bool Return if the validator failed
     */
    public function fails(): bool
    {
        return $this->validate()->failed();
    }

    /**
     * Verify if the validator failed
     * 
     * @return bool Return if the validatior failed
     */
    public function failed(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * Get an array errors
     * 
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get an array errors
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get input
     * 
     * @param  mixed $key
     * @param  mixed $default
     * 
     * @return mixed
     */
    public function input(string $key, $default = null)
    {
        return array_key_exists($key, $this->input)? $this->input[$key]: $default;
    }

    /**
     * Set translator
     * 
     * @param \Symfony\Component\Translation\Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;

        return $this;
    }
}
