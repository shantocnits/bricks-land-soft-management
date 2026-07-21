<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\StockAdjustment;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockKhataTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_khata_page_renders_successfully()
    {
        $user = User::factory()->create();
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\StockKhata::class)
            ->assertStatus(200);
    }

    public function test_can_save_stock_adjustment()
    {
        $user = User::factory()->create();
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\StockKhata::class)
            ->set('date', '2026-07-21')
            ->set('description', 'Test adjustment plus')
            ->set('category', '১ নং')
            ->set('stock_plus', '1000')
            ->set('stock_minus', '')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('stock_adjustments', [
            'description' => 'Test adjustment plus',
            'category_name' => '১ নং',
            'stock_plus' => 1000,
            'stock_minus' => 0,
        ]);
    }

    public function test_validation_fails_if_both_plus_and_minus_are_empty()
    {
        $user = User::factory()->create();
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\StockKhata::class)
            ->set('date', '2026-07-21')
            ->set('description', 'Failing adjustment')
            ->set('category', '১ নং')
            ->set('stock_plus', '')
            ->set('stock_minus', '')
            ->call('save')
            ->assertHasErrors(['stock_plus', 'stock_minus']);
    }

    public function test_can_delete_stock_adjustment()
    {
        $user = User::factory()->create();
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        $adj = StockAdjustment::create([
            'date' => '2026-07-21',
            'description' => 'To delete',
            'category_name' => '১ নং',
            'stock_plus' => 500,
            'stock_minus' => 0,
            'user_id' => $user->id
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\StockKhata::class)
            ->call('deleteAdjustment', $adj->id);

        $this->assertDatabaseMissing('stock_adjustments', [
            'id' => $adj->id
        ]);
    }
}
