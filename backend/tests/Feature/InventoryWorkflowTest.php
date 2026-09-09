<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_and_product_can_be_created_with_a_relationship(): void
    {
        $category = Category::create(['name' => 'Hardware']);

        $response = $this->post(route('products.store'), [
            'name' => 'Widget',
            'sku' => 'W-001',
            'category_id' => $category->id,
            'price' => '12.50',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Widget',
            'sku' => 'W-001',
            'category_id' => $category->id,
            'quantity' => 0,
        ]);
        $this->assertSame('Hardware', Product::first()->category->name);
    }

    public function test_product_validation_rejects_invalid_values_and_duplicate_skus(): void
    {
        $category = Category::create(['name' => 'Hardware']);
        $product = Product::create([
            'name' => 'Widget',
            'sku' => 'W-001',
            'category_id' => $category->id,
            'price' => 12.50,
        ]);

        $this->post(route('products.store'), [
            'name' => '',
            'sku' => 'W-001',
            'category_id' => 999,
            'price' => -1,
        ])->assertSessionHasErrors(['name', 'sku', 'category_id', 'price']);

        $this->put(route('products.update', $product), [
            'name' => 'Renamed widget',
            'sku' => 'W-001',
            'category_id' => $category->id,
            'price' => 10,
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'sku' => 'W-001']);
    }

    public function test_product_quantity_cannot_be_mass_assigned(): void
    {
        $product = Product::create([
            'name' => 'Widget',
            'sku' => 'W-001',
            'category_id' => Category::create(['name' => 'Hardware'])->id,
            'price' => 12.50,
            'quantity' => 4,
        ]);

        $this->assertSame(0, $product->fresh()->quantity);
    }

    public function test_stock_in_and_stock_out_update_quantity_and_create_movements(): void
    {
        $product = $this->product(quantity: 2);

        $this->post(route('stock.store'), [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 5,
            'note' => 'Delivery',
        ])->assertRedirect(route('products.index'));

        $this->post(route('stock.store'), [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 3,
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 4]);
        $this->assertDatabaseCount('stock_movements', 3);
    }

    public function test_stock_out_cannot_exceed_available_quantity_or_create_a_movement(): void
    {
        $product = $this->product(quantity: 2);

        $this->post(route('stock.store'), [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 3,
        ])->assertSessionHasErrors('quantity');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 2]);
        $this->assertDatabaseCount('stock_movements', 1);
    }

    public function test_stock_validation_rejects_zero_decimal_and_invalid_movements(): void
    {
        $product = $this->product();

        foreach ([0, -1, 1.5] as $quantity) {
            $this->post(route('stock.store'), [
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $quantity,
            ])->assertSessionHasErrors('quantity');
        }

        $this->post(route('stock.store'), [
            'product_id' => 999,
            'type' => 'invalid',
            'quantity' => 1,
        ])->assertSessionHasErrors(['product_id', 'type']);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_product_search_and_category_filtering_can_be_combined(): void
    {
        $hardware = Category::create(['name' => 'Hardware']);
        $office = Category::create(['name' => 'Office']);
        Product::create(['name' => 'Blue Widget', 'sku' => 'W-001', 'category_id' => $hardware->id, 'price' => 10]);
        Product::create(['name' => 'Blue Widget', 'sku' => 'W-002', 'category_id' => $office->id, 'price' => 10]);
        Product::create(['name' => 'Red Widget', 'sku' => 'W-003', 'category_id' => $hardware->id, 'price' => 10]);

        $this->get(route('products.index', ['search' => 'Blue', 'category' => $hardware->id]))
            ->assertOk()
            ->assertSee('W-001')
            ->assertDontSee('W-002')
            ->assertDontSee('W-003');
    }

    public function test_category_deletion_succeeds_without_products(): void
    {
        $category = Category::create(['name' => 'Hardware']);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Category deleted successfully.');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_deletion_is_blocked_when_products_are_attached(): void
    {
        $category = Category::create(['name' => 'Hardware']);
        $product = Product::create([
            'name' => 'Widget',
            'sku' => 'W-001',
            'category_id' => $category->id,
            'price' => 12.50,
        ]);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_product_filters_reject_invalid_input(): void
    {
        $this->get(route('products.index', ['category' => 999]))
            ->assertSessionHasErrors('category');

        $this->get(route('products.index', ['search' => str_repeat('x', 256)]))
            ->assertSessionHasErrors('search');
    }

    public function test_product_search_and_category_filtering_accept_valid_input(): void
    {
        $hardware = Category::create(['name' => 'Hardware']);
        $office = Category::create(['name' => 'Office']);
        Product::create(['name' => 'Blue Widget', 'sku' => 'W-001', 'category_id' => $hardware->id, 'price' => 10]);
        Product::create(['name' => 'Blue Widget', 'sku' => 'W-002', 'category_id' => $office->id, 'price' => 10]);

        $this->get(route('products.index', ['search' => 'Blue']))
            ->assertOk()
            ->assertSee('W-001')
            ->assertSee('W-002');

        $this->get(route('products.index', ['category' => $hardware->id]))
            ->assertOk()
            ->assertSee('W-001')
            ->assertDontSee('W-002');
    }

    public function test_dashboard_reports_totals_low_stock_boundary_and_recent_movements(): void
    {
        $product = $this->product(quantity: 5);
        $this->product(name: 'Healthy Widget', sku: 'H-001', quantity: 6);
        StockMovement::create(['product_id' => $product->id, 'type' => 'in', 'quantity' => 1, 'note' => 'Count']);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Total products')
            ->assertSee('Inventory on hand')
            ->assertSee('W-001')
            ->assertSee('Count')
            ->assertDontSee('H-001');
    }

    private function product(
        int $quantity = 0,
        string $name = 'Widget',
        string $sku = 'W-001',
    ): Product {
        $category = Category::firstOrCreate(['name' => 'Hardware']);

        $product = Product::create([
            'name' => $name,
            'sku' => $sku,
            'category_id' => $category->id,
            'price' => 12.50,
        ]);

        if ($quantity > 0) {
            $this->post(route('stock.store'), [
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $quantity,
            ])->assertRedirect(route('products.index'));
        }

        return $product->fresh();
    }
}
