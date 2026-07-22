<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Challan;
use App\Models\ChallanItem;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalesReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_report_page_renders_successfully()
    {
        $user = User::factory()->create();

        $challan = Challan::create([
            'customer_name' => 'Abul Kalam',
            'customer_phone' => '01812345678',
            'customer_address' => 'Gazipur',
            'challan_no' => '1001',
            'date' => '2026-07-22',
            'challan_type' => 'আজকের',
            'grand_total' => 15000,
            'cash' => 10000,
            'due' => 5000
        ]);

        ChallanItem::create([
            'challan_id' => $challan->id,
            'category_name' => '১ নং',
            'rate' => 10,
            'quantity' => 1500,
            'amount' => 15000
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\SalesReport::class)
            ->assertStatus(200)
            ->assertSee('Abul Kalam')
            ->assertSee('01812345678')
            ->assertSee('১০০১')
            ->assertSee('১,৫০০') // quantity
            ->assertSee('১৫,০০০') // grand total
            ->assertSee('১০,০০০') // cash paid
            ->assertSee('৫,০০০'); // due
    }

    public function test_sales_report_search_filter()
    {
        $user = User::factory()->create();

        Challan::create([
            'customer_name' => 'Unique Buyer',
            'customer_phone' => '01999999999',
            'challan_no' => '9999',
            'grand_total' => 8000,
            'cash' => 8000,
            'due' => 0
        ]);

        Challan::create([
            'customer_name' => 'Other Buyer',
            'customer_phone' => '01111111111',
            'challan_no' => '1111',
            'grand_total' => 3000,
            'cash' => 3000,
            'due' => 0
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\SalesReport::class)
            ->set('search', 'Unique')
            ->assertSee('Unique Buyer')
            ->assertDontSee('Other Buyer');
    }
}
