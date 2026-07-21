<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Challan;
use App\Models\ChallanItem;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_page_renders_successfully()
    {
        $user = User::factory()->create();

        $challan = Challan::create([
            'customer_name' => 'John Doe',
            'customer_phone' => '01712345678',
            'customer_address' => 'Dhaka',
            'grand_total' => 5000,
            'cash' => 4000,
            'due' => 1000,
            'due_payment_date' => '2026-07-30'
        ]);

        ChallanItem::create([
            'challan_id' => $challan->id,
            'category_name' => '১ নং ইট',
            'rate' => 10,
            'quantity' => 500,
            'amount' => 5000,
            'delivered_quantity' => 200
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Customer::class)
            ->assertStatus(200)
            ->assertSee('John Doe')
            ->assertSee('01712345678')
            ->assertSee('Dhaka')
            ->assertSee('৫০০') // Purchased quantity
            ->assertSee('২০০') // Delivered quantity
            ->assertSee('৩০০') // Delivery remaining
            ->assertSee('৫,০০০') // Total value
            ->assertSee('৪,০০০') // Paid cash
            ->assertSee('১,০০০'); // Due amount
    }

    public function test_can_update_customer_info()
    {
        $user = User::factory()->create();

        $challan = Challan::create([
            'customer_name' => 'Old Name',
            'customer_phone' => '01700000000',
            'customer_address' => 'Old Address',
            'grand_total' => 1000,
            'cash' => 1000,
            'due' => 0
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Customer::class)
            ->call('openUpdateModal', '01700000000', 'Old Name')
            ->assertSet('updateName', 'Old Name')
            ->set('updateName', 'New Name')
            ->set('updatePhone', '01799999999')
            ->set('updateAddress', 'New Address')
            ->call('saveCustomerInfo');

        // Check if database updated
        $this->assertDatabaseHas('challans', [
            'customer_name' => 'New Name',
            'customer_phone' => '01799999999',
            'customer_address' => 'New Address'
        ]);
    }

    public function test_can_update_due_payment_date()
    {
        $user = User::factory()->create();

        $challan = Challan::create([
            'customer_name' => 'Some Name',
            'customer_phone' => '01711111111',
            'due_payment_date' => '2026-07-21'
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Customer::class)
            ->call('openDateModal', '01711111111', 'Some Name')
            ->set('newDueDate', '2026-08-15')
            ->set('dueDateNotes', 'Updated note details')
            ->call('saveDueDate');

        $this->assertDatabaseHas('challans', [
            'customer_phone' => '01711111111',
            'due_payment_date' => '2026-08-15',
            'notes' => 'Updated note details'
        ]);
    }
}
