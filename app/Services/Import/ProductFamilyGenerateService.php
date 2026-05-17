<?php

namespace App\Services\ProductFamilyGenerate;

use App\Models\Product;
use App\Models\ProductFamily;
use Illuminate\Support\Str;

class ProductFamilyGenerateService
{
    public function handle(): void
    {
        Product::query()
            ->whereNull('product_family_id')
            ->chunk(100, function ($products) {
                foreach ($products as $product) {

                    $familyName = $this->makeFamilyName($product);

                    $family = ProductFamily::firstOrCreate(
                        [
                            'slug' => Str::slug($familyName),
                        ],
                        [
                            'name' => $familyName,
                        ]
                    );

                    $product->update([
                        'product_family_id' => $family->id,
                    ]);
                }
            });
    }

    protected function makeFamilyName(Product $product): string
    {
        return trim($product->name);
    }
}
