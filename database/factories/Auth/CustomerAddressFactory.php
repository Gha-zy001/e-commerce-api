<?php

namespace Database\Factories\Auth;

use App\Models\Auth\Customer;
use App\Models\Auth\CustomerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerAddress>
 */
class CustomerAddressFactory extends Factory
{
    protected $model = CustomerAddress::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'label' => fake()->randomElement(['home', 'work', 'other']),
            'country' => 'EG',
            'governorate' => fake()->city(),
            'city' => fake()->city(),
            'district' => fake()->streetName(),
            'street' => fake()->streetAddress(),
            'building' => fake()->buildingNumber(),
            'floor' => fake()->randomDigitNotZero(),
            'apartment' => fake()->randomDigitNotZero(),
            'postal_code' => fake()->postcode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
        ]);
    }
}
