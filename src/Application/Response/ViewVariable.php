<?php

declare(strict_types=1);

namespace App\Application\Response;

use App\Application\Helpers\Helper;
use Stringable;
use ArrayAccess;
use IteratorAggregate;
use ReturnTypeWillChange;
use Traversable;
use BadMethodCallException;

class ViewVariable implements Stringable, ArrayAccess, IteratorAggregate
{
    private mixed $value;

    public function __construct(mixed $value) { $this->value = $value; }

    public function dangerousRaw(): mixed { return $this->value; }

    public function __toString(): string
    {
        if (is_string($this->value)) return htmlspecialchars($this->value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        if (is_null($this->value)) return '';
        if (is_bool($this->value)) return $this->value ? '1' : '0';
        if (is_numeric($this->value)) return (string) $this->value;
        if ($this->value instanceof Stringable || (is_object($this->value) && method_exists($this->value, '__toString'))) {
            return htmlspecialchars((string) $this->value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        return '';
    }

    public function __get(string $name): mixed
    {
        return is_object($this->value) && isset($this->value->$name) ? new self($this->value->$name) : new self(null);
    }

    public function __call(string $name, array $args): mixed
    {
        $unwrapped = array_map(fn($arg) => ($arg instanceof self) ? $arg->dangerousRaw() : $arg, $args);

        // 1. Appel direct sur l'objet encapsulé (ex: $user->getName())
        if (is_object($this->value) && is_callable([$this->value, $name])) {
            return new self(call_user_func_array([$this->value, $name], $unwrapped));
        }

        // 2. Raccourci Helper (ex: $message->slugify())
        if ($container = Helper::getContainer()) {
            return new self($container->get(Helper::class)->$name($this->dangerousRaw(), ...$unwrapped));
        }

        throw new BadMethodCallException("Method {$name} not found on value or Helper system.");
    }

    public function __invoke(...$args): mixed
    {
        if (is_callable($this->value)) {
            $unwrapped = array_map(fn($arg) => ($arg instanceof self) ? $arg->dangerousRaw() : $arg, $args);
            return new self(($this->value)(...$unwrapped));
        }
        throw new BadMethodCallException("Value is not callable.");
    }

    public function offsetExists(mixed $offset): bool { return isset($this->value[$offset]); }
    #[ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed { return new self($this->value[$offset] ?? null); }
    public function offsetSet(mixed $offset, mixed $value): void {}
    public function offsetUnset(mixed $offset): void {}

    public function getIterator(): Traversable
    {
        if (is_iterable($this->value)) {
            foreach ($this->value as $key => $val) yield $key => new self($val);
        }
    }
}
