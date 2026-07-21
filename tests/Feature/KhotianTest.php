<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KhotianTest extends TestCase
{
    use RefreshDatabase;

    public function test_khotian_page_renders_successfully()
    {
        $user = User::factory()->create();
        
        Payment::create([
            'date' => '18/07/2026',
            'ledger' => 'অন্যান্য',
            'desc' => 'Test details',
            'qty' => 100,
            'rate' => 10,
            'total' => 1000,
            'advance' => 0,
            'deduction' => 0,
            'payment' => 500,
            'purchase_receive' => 0,
            'has_doc' => false
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Khotian::class)
            ->assertStatus(200)
            ->assertSee('অন্যান্য');
    }

    public function test_can_select_ledger_and_view_details()
    {
        $user = User::factory()->create();
        
        Payment::create([
            'date' => '18/07/2026',
            'ledger' => 'মেল',
            'desc' => 'Test details मेल',
            'qty' => 200,
            'rate' => 5,
            'total' => 1000,
            'advance' => 100,
            'deduction' => 10,
            'payment' => 900,
            'purchase_receive' => 0,
            'has_doc' => false
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Khotian::class)
            ->call('selectLedger', 'মেল')
            ->assertSet('selectedLedger', 'মেল')
            ->assertSee('Test details मेल')
            ->assertSee('৯০০');
    }

    public function test_can_filter_details_by_date_range()
    {
        $user = User::factory()->create();
        
        // 18/07/2026
        Payment::create([
            'date' => '18/07/2026',
            'ledger' => 'ক্লিন পরিষ্কার',
            'desc' => 'Inside range record',
            'qty' => 10,
            'rate' => 10,
            'total' => 100,
            'advance' => 0,
            'deduction' => 0,
            'payment' => 100,
            'purchase_receive' => 0,
            'has_doc' => false
        ]);

        // 25/07/2026
        Payment::create([
            'date' => '25/07/2026',
            'ledger' => 'ক্লিন পরিষ্কার',
            'desc' => 'Outside range record',
            'qty' => 10,
            'rate' => 10,
            'total' => 100,
            'advance' => 0,
            'deduction' => 0,
            'payment' => 100,
            'purchase_receive' => 0,
            'has_doc' => false
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Khotian::class)
            ->call('selectLedger', 'ক্লিন পরিষ্কার')
            ->set('startDate', '2026-07-15')
            ->set('endDate', '2026-07-20')
            ->assertSee('Inside range record')
            ->assertDontSee('Outside range record');
    }
}
