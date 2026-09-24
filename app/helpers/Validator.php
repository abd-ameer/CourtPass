<?php
/**
 * Simple input validation. Collects errors per field.
 *
 *   $v = new Validator($request->all());
 *   $v->required('email')->email('email')->required('password')->minLength('password', 8);
 *   if ($v->fails()) { ... $v->errors() ... }
 */
class Validator
{
    private array $errors = [];

    public function __construct(private array $data)
    {
    }

    private function value(string $field): string
    {
        return trim((string) ($this->data[$field] ?? ''));
    }

    /** Character count (uses mbstring when available so Sinhala/Tamil text counts correctly). */
    private function length(string $field): int
    {
        $v = $this->value($field);
        return function_exists('mb_strlen') ? mb_strlen($v) : strlen($v);
    }

    private function addError(string $field, string $message): self
    {
        $this->errors[$field] ??= $message;
        return $this;
    }

    public function required(string $field, string $label = ''): self
    {
        return $this->value($field) === '' ? $this->addError($field, ($label ?: ucfirst($field)) . ' is required.') : $this;
    }

    public function email(string $field): self
    {
        $v = $this->value($field);
        return $v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL) ? $this->addError($field, 'Enter a valid email address.') : $this;
    }

    public function minLength(string $field, int $min): self
    {
        return $this->length($field) < $min ? $this->addError($field, "Must be at least {$min} characters.") : $this;
    }

    public function maxLength(string $field, int $max): self
    {
        return $this->length($field) > $max ? $this->addError($field, "Must be at most {$max} characters.") : $this;
    }

    /**
     * Passwords are checked exactly as typed (no trimming).
     * bcrypt only reads the first 72 bytes, and Sinhala or Tamil letters take 3 bytes each.
     */
    public function password(string $field, int $min = 8): self
    {
        $raw = (string) ($this->data[$field] ?? '');
        $chars = function_exists('mb_strlen') ? mb_strlen($raw) : strlen($raw);
        if ($chars < $min) {
            return $this->addError($field, "Must be at least {$min} characters.");
        }
        if (strlen($raw) > 72) {
            return $this->addError($field, 'Too long. Use at most 72 English characters (Sinhala and Tamil letters count as 3).');
        }
        return $this;
    }

    public function integer(string $field, ?int $min = null, ?int $max = null): self
    {
        $v = filter_var($this->value($field), FILTER_VALIDATE_INT);
        if ($v === false || ($min !== null && $v < $min) || ($max !== null && $v > $max)) {
            return $this->addError($field, 'Enter a valid whole number.');
        }
        return $this;
    }

    /** Money or other decimal amount with at most two decimal places. */
    public function decimal(string $field, ?float $min = null, ?float $max = null): self
    {
        $v = $this->value($field);
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $v)) {
            return $this->addError($field, 'Enter a valid amount.');
        }
        if (($min !== null && (float) $v < $min) || ($max !== null && (float) $v > $max)) {
            return $this->addError($field, 'Enter an amount in the allowed range.');
        }
        return $this;
    }

    /** Slot start time on the hour, e.g. 18:00 (seconds allowed as :00). */
    public function hour(string $field): self
    {
        return preg_match('/^([01]\d|2[0-3]):00(:00)?$/', $this->value($field)) ? $this : $this->addError($field, 'Choose a start time on the hour.');
    }

    public function in(string $field, array $allowed): self
    {
        return !in_array($this->value($field), $allowed, true) ? $this->addError($field, 'Invalid option selected.') : $this;
    }

    public function date(string $field, string $format = 'Y-m-d'): self
    {
        $d = DateTime::createFromFormat($format, $this->value($field));
        return !$d || $d->format($format) !== $this->value($field) ? $this->addError($field, 'Enter a valid date.') : $this;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
