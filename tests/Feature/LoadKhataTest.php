<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LoadRound;
use App\Models\LoadEntry;
use App\Models\Category;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoadKhataTest extends TestCase
{
    use RefreshDatabase;

    public function test_load_khata_modal_can_be_opened()
    {
        $user = User::factory()->create();
        LoadRound::create(['name' => '১ নম্বর রাউন্ড']);
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\LoadKhata::class)
            ->call('openModal')
            ->assertSet('showModal', true);
    }

    public function test_can_save_new_load_entry_with_category()
    {
        $user = User::factory()->create();
        LoadRound::create(['name' => '১ নম্বর রাউন্ড']);
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\LoadKhata::class)
            ->call('openModal')
            ->set('date', '2026-07-21')
            ->set('round', '১ নম্বর রাউন্ড')
            ->set('description', 'পাকা ইট লোড হয়েছে')
            ->set('category', '১ নং')
            ->set('quantity', '5000')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('load_entries', [
            'description' => 'পাকা ইট লোড হয়েছে',
            'category' => '১ নং',
            'quantity' => 5000,
        ]);
    }

    public function test_category_is_optional_by_default()
    {
        $user = User::factory()->create();
        LoadRound::create(['name' => '১ নম্বর রাউন্ড']);
        Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);

        // Should succeed even if category is empty for পাকা ইট লোড হয়েছে
        Livewire::actingAs($user)
            ->test(\App\Livewire\LoadKhata::class)
            ->call('openModal')
            ->set('date', '2026-07-21')
            ->set('round', '১ নম্বর রাউন্ড')
            ->set('description', 'পাকা ইট লোড হয়েছে')
            ->set('category', '')
            ->set('quantity', '5000')
            ->call('save')
            ->assertHasNoErrors();

        // Should succeed without category for field load
        Livewire::actingAs($user)
            ->test(\App\Livewire\LoadKhata::class)
            ->call('openModal')
            ->set('date', '2026-07-21')
            ->set('round', '১ নম্বর রাউন্ড')
            ->set('description', 'মাঠ থেকে লোড হয়েছে')
            ->set('category', '')
            ->set('quantity', '5000')
            ->call('save')
            ->assertHasNoErrors();
    }
}
