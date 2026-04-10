<?php

declare(strict_types=1);

namespace App\Application\Response;

use App\Application\Helpers\Helper;
use Stringable;
use ArrayAccess;
use IteratorAggregate;
use Countable;
use ReturnTypeWillChange;
use Traversable;
use BadMethodCallException;

class ViewVariable implements Stringable, ArrayAccess, IteratorAggregate, Countable
{
    private mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function dangerousRaw(): mixed
    {
        return $this->value;
    }

    public function __toString(): string
    {
        if (is_string($this->value)) {
            return htmlspecialchars($this->value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        if (is_null($this->value)) {
            return '';
        }
        if (is_bool($this->value)) {
            return $this->value ? '1' : '0';
        }
        if (is_numeric($this->value)) {
            return (string) $this->value;
        }
        if ($this->value instanceof Stringable || (is_object($this->value) && method_exists($this->value, '__toString'))) {
            return htmlspecialchars((string) $this->value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        return '';
    }

    public function __get(string $name): mixed
    {
        $val = null;
        if (is_object($this->value) && isset($this->value->$name)) {
            $val = $this->value->$name;
        } elseif (is_array($this->value) && isset($this->value[$name])) {
            $val = $this->value[$name];
        }

        // Si la valeur est nulle ou n'existe pas, on retourne null (falsy dans un if)
        // Si c'est un scalaire "vide" (0, "", false), on le retourne tel quel pour la vérité PHP
        // Sinon on retourne un nouveau wrapper pour continuer le chaînage/XSS
        if ($val === null || (is_scalar($val) && empty($val))) {
            return $val;
        }
        return new self($val);
    }

    public function __call(string $name, array $args): mixed
    {
        $unwrapped = array_map(fn($arg) => ($arg instanceof self) ? $arg->dangerousRaw() : $arg, $args);

        if (is_object($this->value) && is_callable([$this->value, $name])) {
            return new self(call_user_func_array([$this->value, $name], $unwrapped));
        }

        if ($container = Helper::getContainer()) {
            $h = $container->get(Helper::class);
            try {
                return new self($h->$name($this->dangerousRaw(), ...$unwrapped));
            } catch (\BadMethodCallException $e) {
                // Not found in helpers either, we fall through to the final throw
            } catch (\Throwable $e) {
                // Real error in helper call, rethrow it
                throw $e;
            }
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

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->value[$offset]);
    }
    #[ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get((string)$offset);
    }
    public function offsetSet(mixed $offset, mixed $value): void
    {
    }
    public function offsetUnset(mixed $offset): void
    {
    }

    public function getIterator(): Traversable
    {
        if (is_iterable($this->value)) {
            foreach ($this->value as $key => $val) {
                yield $key => new self($val);
            }
        }
    }

    public function count(): int
    {
        if (is_array($this->value) || $this->value instanceof Countable) {
            return count($this->value);
        }
        return 0;
    }
}
