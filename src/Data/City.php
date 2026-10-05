<?php

namespace FLAIRUK\Cities\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @implements Arrayable<string, int|string>
 */
final readonly class City implements Arrayable, JsonSerializable
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $countryCode,
    ) {}

    /**
     * @param  array{id: int, code: string, name: string, country_code: string}  $row
     */
    public static function fromArray(array $row): self
    {
        return new self(
            id: $row['id'],
            code: $row['code'],
            name: $row['name'],
            countryCode: $row['country_code'],
        );
    }

    /**
     * @return array{id: int, code: string, name: string, country_code: string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'country_code' => $this->countryCode,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
