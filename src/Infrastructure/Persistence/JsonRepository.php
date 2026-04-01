<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use RuntimeException;

abstract class JsonRepository
{
    protected array $data = [];
    protected array $queryData = [];
    protected string $collection;
    protected string $filePath;
    protected ?string $modelClass = null;

    public function __construct(string $collection, ?string $modelClass = null)
    {
        $this->collection = $collection;
        $this->modelClass = $modelClass;
        $this->filePath = ROOT . DIRECTORY_SEPARATOR . 'data.json';
        $this->loadData();
        $this->queryData = $this->data;
    }

    protected function loadData(): void
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Data file not found: {$this->filePath}");
        }

        $json = file_get_contents($this->filePath);
        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Error decoding JSON: " . json_last_error_msg());
        }

        $this->data = $decoded[$this->collection] ?? [];
    }

    protected function map(array $item): mixed
    {
        if ($this->modelClass && class_exists($this->modelClass)) {
            return new $this->modelClass($item);
        }
        return $item;
    }

    public function findAll(): array
    {
        return array_map([$this, 'map'], $this->data);
    }

    /**
     * Identique à findBy() de Doctrine mais retourne un seul objet.
     */
    public function findOneBy(string $key, mixed $value): mixed
    {
        foreach ($this->data as $item) {
            if (isset($item[$key]) && $item[$key] === $value) {
                return $this->map($item);
            }
        }
        return null;
    }

    public function find(int $id): mixed
    {
        return $this->findOneBy('id', $id);
    }

    // --- Query Builder Methods (Fluent Interface) ---

    public function where(string $key, mixed $value): static
    {
        $this->queryData = array_values(array_filter($this->queryData, function ($item) use ($key, $value) {
            return isset($item[$key]) && $item[$key] === $value;
        }));
        return $this;
    }

    public function filter(callable $callback): static
    {
        $this->queryData = array_values(array_filter($this->queryData, $callback));
        return $this;
    }

    public function limit(int $n): static
    {
        $this->queryData = array_slice($this->queryData, 0, $n);
        return $this;
    }

    public function latest(): static
    {
        usort($this->queryData, fn($a, $b) => ($b['id'] ?? 0) <=> ($a['id'] ?? 0));
        return $this;
    }

    public function get(): array
    {
        $results = array_map([$this, 'map'], $this->queryData);
        $this->queryData = $this->data; // Reset
        return $results;
    }
}
