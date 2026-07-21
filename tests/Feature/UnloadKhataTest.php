<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LoadRound;
use App\Models\LoadEntry;
use App\Models\Category;
use App\Models\UnloadEntry;
use App\Models\UnloadItem;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnloadKhataTest extends TestCase
{
    use RefreshDatabase;

    public function test_unload_khata_page_is_displayed_for_authenticated_users()
    {
        $user = User::factory()->create();
        LoadRound::create(['name' => '১ নম্বর রাউন্ড']);
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        $response = $this->actingAs($user)->get('/unload-khata');
        $response->assertOk();
    }

    public function test_can_save_new_unload_entry()
    {
        $user = User::factory()->create();
        LoadRound::create(['name' => '১ নম্বর রাউন্ড']);
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\UnloadKhata::class)
            ->call('openModal')
            ->set('date', '2026-07-21')
            ->set('round', '১ নম্বর রাউন্ড')
            ->set('category', '১ নং')
            ->set('quantity', '4000')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('unload_entries', [
            'round' => '১ নম্বর রাউন্ড',
        ]);

        $this->assertDatabaseHas('unload_items', [
            'category_name' => '১ নং',
            'quantity'      => 4000,
        ]);
    }

    public function test_zero_quantity_removes_unload_item()
    {
        $user = User::factory()->create();
        LoadRound::create(['name' => '১ নম্বর রাউন্ড']);
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        // Create an existing entry first
        $entry = UnloadEntry::create(['date' => '2026-07-21', 'round' => '১ নম্বর রাউন্ড']);
        $item = $entry->items()->create(['category_name' => '১ নং', 'quantity' => 4000]);

        // Set quantity to 0 to trigger removal
        Livewire::actingAs($user)
            ->test(\App\Livewire\UnloadKhata::class)
            ->set('date', '2026-07-21')
            ->set('round', '১ নম্বর রাউন্ড')
            ->set('category', '১ নং')
            ->set('quantity', '0')
            ->call('save')
            ->assertHasNoErrors();

        // The item and empty entry should be deleted from the database
        $this->assertDatabaseMissing('unload_items', [
            'id' => $item->id,
        ]);

        $this->assertDatabaseMissing('unload_entries', [
            'id' => $entry->id,
        ]);
    }
}
