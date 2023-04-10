<?php

namespace Model;
interface ModelInterface
{
    function all(): array;
    function get(int $id): self;
    function save(array $data): self;
    function update(array $data): self;
    function delete(): bool;
    function getRules(): array;
    function toString(): string;
    function toArray(): array;
}