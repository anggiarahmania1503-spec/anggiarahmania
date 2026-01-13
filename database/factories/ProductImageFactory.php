<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

// Nama class HARUS ProductImageFactory agar sesuai dengan nama filenya
class ProductImageFactory extends Factory
{
    /**
     * Nama model yang terkait dengan factory ini.
     */
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // Ganti 'product_id' sesuai dengan foreign key di tabel product_images Anda
            'product_id' => \App\Models\Product::factory(), 
            'image_path' => 'products/' . fake()->image(null, 640, 480, 'fashion', false),
            'is_primary' => false,
        ];
    }

    /**
     * State untuk gambar utama.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }
}