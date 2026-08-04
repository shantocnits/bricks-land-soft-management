<?php

namespace Tests\Feature;

use App\Models\CashEntry;
use App\Models\Challan;
use App\Models\Payment;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CashKhataTest extends TestCase
{
    use RefreshDatabase;

    public function test_cash_khata_page_renders_successfully()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->assertStatus(200);
    }

    public function test_open_modal_is_blocked_for_non_today_date()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->set('dateFilter', '2026-07-20')
            ->call('openModal')
            ->assertSet('showModal', false)
            ->assertDispatched('cash-toast', message: 'দুঃখিত, আজকের তারিখ ব্যতীত অন্য তারিখে ক্যাশের হিসাব ইনপুট দেওয়ার অনুমতি নেই', type: 'error');
    }

    public function test_open_modal_opens_for_today_date()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->call('openModal')
            ->assertSet('showModal', true)
            ->assertSet('date', now()->format('Y-m-d'));
    }

    public function test_save_creates_cash_in_entry()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->set('entryType', 'in')
            ->set('description', 'ইট বিক্রি')
            ->set('amount', '500')
            ->set('date', now()->format('Y-m-d'))
            ->set('time', '10:00:00')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('cash-toast', type: 'success');

        $this->assertDatabaseHas('cash_entries', [
            'description' => 'ইট বিক্রি',
            'cash_in' => 500,
            'cash_out' => null,
            'is_system' => false,
        ]);
    }

    public function test_save_creates_cash_out_entry()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->set('entryType', 'out')
            ->set('description', 'মালামাল কেনা')
            ->set('amount', '300')
            ->set('date', now()->format('Y-m-d'))
            ->set('time', '11:00:00')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cash_entries', [
            'description' => 'মালামাল কেনা',
            'cash_in' => null,
            'cash_out' => 300,
            'is_system' => false,
        ]);
    }

    public function test_save_validates_required_fields()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->set('description', '')
            ->set('amount', '')
            ->call('save')
            ->assertHasErrors(['description', 'amount']);
    }

    public function test_new_entry_cannot_be_saved_on_non_today_date()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->set('entryType', 'in')
            ->set('description', 'পুরনো তারিখের এন্ট্রি')
            ->set('amount', '100')
            ->set('date', '2026-07-20')
            ->set('time', '10:00:00')
            ->call('save')
            ->assertDispatched('cash-toast', type: 'error');

        $this->assertDatabaseMissing('cash_entries', [
            'description' => 'পুরনো তারিখের এন্ট্রি',
        ]);
    }

    public function test_edit_updates_user_created_entry()
    {
        $user = User::factory()->create();

        $entry = CashEntry::create([
            'description' => 'পুরনো বিবরণ',
            'cash_in' => 100,
            'cash_out' => null,
            'date' => now()->format('Y-m-d'),
            'time' => '09:00:00',
            'is_system' => false,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->call('edit', $entry->id)
            ->assertSet('showModal', true)
            ->set('description', 'নতুন বিবরণ')
            ->set('amount', '250')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cash_entries', [
            'id' => $entry->id,
            'description' => 'নতুন বিবরণ',
            'cash_in' => 250,
        ]);
    }

    public function test_edit_is_blocked_for_system_generated_entry()
    {
        $user = User::factory()->create();

        $systemEntry = CashEntry::create([
            'description' => 'নগদ ইট বিক্রি',
            'cash_in' => null,
            'cash_out' => null,
            'date' => now()->format('Y-m-d'),
            'time' => '12:00:00',
            'is_system' => true,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->call('edit', $systemEntry->id)
            ->assertSet('showModal', false)
            ->assertDispatched('cash-toast', type: 'error');

        $this->assertDatabaseHas('cash_entries', [
            'id' => $systemEntry->id,
            'description' => 'নগদ ইট বিক্রি',
        ]);
    }

    public function test_delete_is_blocked_for_system_generated_entry()
    {
        $user = User::factory()->create();

        $systemEntry = CashEntry::create([
            'description' => 'বাকি কালেকশন',
            'cash_in' => 667,
            'cash_out' => null,
            'date' => now()->format('Y-m-d'),
            'time' => '12:30:00',
            'is_system' => true,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->call('delete', $systemEntry->id)
            ->assertDispatched('cash-toast', type: 'error');

        $this->assertDatabaseHas('cash_entries', [
            'id' => $systemEntry->id,
        ]);
    }

    public function test_delete_removes_user_created_entry()
    {
        $user = User::factory()->create();

        $entry = CashEntry::create([
            'description' => 'মুছে ফেলার বিবরণ',
            'cash_in' => 50,
            'cash_out' => null,
            'date' => now()->format('Y-m-d'),
            'time' => '08:00:00',
            'is_system' => false,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->call('delete', $entry->id)
            ->assertDispatched('cash-toast', type: 'success');

        $this->assertDatabaseMissing('cash_entries', [
            'id' => $entry->id,
        ]);
    }

    public function test_warning_banner_is_rendered()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->assertSee('এই হিসাব ক্যাশ খাতা থেকে পরিবর্তন করা যাবে না');
    }

    public function test_cash_report_print_layout_is_rendered_in_page()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->assertSee('দৈনিক ক্যাশ রিপোর্ট')
            ->assertSee('মোট ক্যাশ ইন')
            ->assertSee('নেট ব্যালেন্স')
            ->assertSee('ম্যানেজার')
            ->assertSee('মালিক');
    }

    public function test_system_rows_always_present_even_without_transactions()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->assertSee('নগদ ইট বিক্রি')
            ->assertSee('বাকি কালেকশন')
            ->assertSee('মোট পেমেন্ট দেওয়া');
    }

    public function test_system_rows_show_sale_cash_from_challans()
    {
        $user = User::factory()->create();

        Challan::create([
            'customer_name' => 'রফিক',
            'challan_no' => 'CH-1001',
            'date' => now()->toDateString(),
            'grand_total' => 5000,
            'cash' => 2000,
            'due' => 3000,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->assertSee('নগদ ইট বিক্রি')
            ->assertSee('৳ ২,০০০');
    }

    public function test_system_rows_show_collection_cash_from_challans()
    {
        $user = User::factory()->create();

        Challan::create([
            'customer_name' => 'করিম',
            'challan_no' => 'CH-1002',
            'date' => now()->toDateString(),
            'grand_total' => 0,
            'cash' => 1500,
            'due' => 0,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->assertSee('বাকি কালেকশন')
            ->assertSee('৳ ১,৫০০');
    }

    public function test_system_rows_show_payment_out_from_payment_khata()
    {
        $user = User::factory()->create();

        Payment::create([
            'date' => now()->toDateString(),
            'ledger' => 'মহাজন',
            'desc' => 'মালামালের দেনা',
            'payment' => 800,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->assertSee('মোট পেমেন্ট দেওয়া')
            ->assertSee('৳ ৮০০');
    }

    public function test_system_rows_are_filtered_by_selected_date()
    {
        $user = User::factory()->create();

        Challan::create([
            'customer_name' => 'সালাম',
            'challan_no' => 'CH-1003',
            'date' => now()->subDay()->toDateString(),
            'grand_total' => 4000,
            'cash' => 4000,
            'due' => 0,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\CashKhata::class)
            ->set('dateFilter', now()->subDay()->toDateString())
            ->assertSee('৳ ৪,০০০')
            ->set('dateFilter', now()->toDateString())
            ->assertDontSee('৳ ৪,০০০');
    }
}
