<?php

namespace Database\Factories;

use App\Enums\ProviderTypeCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     * TODO that is the default auth credintials for Accurate
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FjY3VyYXRlLmFjY3VyYXRlc3MuY29tOjgwMDEvZ3JhcGhxbCIsImlhdCI6MTcwMDk4NjcwMSwiZXhwIjoxNzA5NjI2NzAxLCJuYmYiOjE3MDA5ODY3MDEsImp0aSI6ImJIbmFZOTkzRjh5Yk41S1ciLCJzdWIiOiIyOSIsInBydiI6ImNjYzkzY2JiY2U3ZTExNTI2ZTc2ZjYyYTFkYTgxNTExMTYzMTY1MmYiLCJsb2dpbl9pZCI6NjMwfQ.TLMlE5WDS1LfVjdJkEAVGOu5_WzFUGQPaNMk8FrFfBQ', // password
            'url' => 'http://192.168.1.31:8001/graphql',
            // 'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FjY3VyYXRlLmFjY3VyYXRlc3MuY29tOjgwMDEvZ3JhcGhxbCIsImlhdCI6MTcwMDk4NjcwMSwiZXhwIjoxNzA5NjI2NzAxLCJuYmYiOjE3MDA5ODY3MDEsImp0aSI6ImJIbmFZOTkzRjh5Yk41S1ciLCJzdWIiOiIyOSIsInBydiI6ImNjYzkzY2JiY2U3ZTExNTI2ZTc2ZjYyYTFkYTgxNTExMTYzMTY1MmYiLCJsb2dpbl9pZCI6NjMwfQ.TLMlE5WDS1LfVjdJkEAVGOu5_WzFUGQPaNMk8FrFfBQ', // password
            // 'url' => 'http://accurate.accuratess.com:8001/graphql',
            'type_code' => (ProviderTypeCode::DELIVERY_AGENT)->value,
        ];
    }
}
