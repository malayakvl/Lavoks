<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductFamily;
use Illuminate\Support\Str;

class ProductFamilyGenerateService
{
    /**
     * Generate product families based on common naming patterns
     */
    public function generate(): int
    {
        $products = Product::with('translations')->get();
        $familiesCreated = 0;

        // Group products by similar name patterns
        $groups = [];
        
        foreach ($products as $product) {
            // Get Ukrainian title from translations
            $title = $product->translations()->where('locale', 'uk')->first()?->title;
            
            if (!$title) {
                continue;
            }

            // Extract base name (remove numbers, sizes, etc.)
            $baseName = $this->extractBaseName($title);
            
            if (!isset($groups[$baseName])) {
                $groups[$baseName] = [];
            }
            
            $groups[$baseName][] = $product;
        }

        // Create families for all unique base names
        foreach ($groups as $baseName => $products) {
            $slug = Str::slug($baseName);
            
            // Check if family already exists
            $family = ProductFamily::where('slug', $slug)->first();
            
            if (!$family) {
                $family = ProductFamily::create([
                    'name' => $baseName,
                    'slug' => $slug,
                ]);
                
                $familiesCreated++;
            }

            // Link products to family
            foreach ($products as $product) {
                $product->update([
                    'product_family_id' => $family->id,
                ]);
            }
        }

        return $familiesCreated;
    }

    /**
     * Extract base name from product title
     */
    private function extractBaseName(string $title): string
    {
        // Remove common patterns like sizes, colors, etc.
        // This is a simple implementation - adjust regex as needed
        $baseName = preg_replace('/[\s-]+\d+[\sxXxхХ].*$/', '', $title);
        $baseName = preg_replace('/[\s-]+(чорний|білий|синій|червоний|зелений).*$/i', '', $baseName);
        
        return trim($baseName);
    }
}
