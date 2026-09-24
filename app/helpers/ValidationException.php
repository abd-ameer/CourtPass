<?php
/**
 * Thrown by a service when input breaks a business rule.
 * Carries field => message errors that the form views already render.
 */
class ValidationException extends RuntimeException
{
    public function __construct(private array $errors)
    {
        parent::__construct((string) (reset($errors) ?: 'The submitted data is not valid.'));
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
