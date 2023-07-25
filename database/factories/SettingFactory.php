<?php

namespace Database\Factories;

use App\Enums\UserTypeCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYWNjdXJhdGUuYWNjdXJhdGVzcy5jb206ODAwMC9ncmFwaHFsIiwiaWF0IjoxNjg5ODAyMjQ4LCJleHAiOjE2OTg0NDIyNDgsIm5iZiI6MTY4OTgwMjI0OCwianRpIjoiUXVxcmVwSng4SkFIcjVYbSIsInN1YiI6IjI5IiwicHJ2IjoiY2NjOTNjYmJjZTdlMTE1MjZlNzZmNjJhMWRhODE1MTExNjMxNjUyZiIsImxvZ2luX2lkIjo4NTF9.0koRQFilZqzUzLJD30U7woEeVPCHSubPaSaSCjq687w', // password
            'url' => 'http://accurate.accuratess.com:8000/graphql',
            'type_code' => (UserTypeCode::DELIVERY_AGENT)->value,
        ];
    }
}
