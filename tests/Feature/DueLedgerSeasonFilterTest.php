<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Challan;
use App\Models\Setting;
use Livewire\Livewire;
use App\Livewire\DueLedger\AllDueList;
use App\Livewire\DueLedger\TodayCollection;
use App\Livewire\DueLedger\DueToday;

class DueLedgerSeasonFilterTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_all_due_list_page_renders_with_season_filter()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Challan::create([
            'customer_type' => 'old',
            'customer_name' => 'Test Customer',
            'customer_phone' => '0123456789',
            'challan_no' => '1',
            'date' => now()->toDateString(),
            'challan_type' => 'আজকের',
            'grand_total' => 500,
            'cash' => 200,
            'due' => 300,
            'due_payment_date' => now()->toDateString(),
            'season' => '২৫-২৬',
        ]);

        Challan::create([
            'customer_type' => 'old',
            'customer_name' => 'Old Season Customer',
            'customer_phone' => '0987654321',
            'challan_no' => '2',
            'date' => now()->toDateString(),
            'challan_type' => 'আজকের',
            'grand_total' => 500,
            'cash' => 0,
            'due' => 500,
            'due_payment_date' => now()->toDateString(),
            'season' => '২৩-২৪',
        ]);

        Livewire::test(AllDueList::class)
            ->assertViewHas('seasons', function ($seasons) {
                return $seasons->count() === 3
                    && $seasons->contains('২৩-২৪')
                    && $seasons->contains('২৪-২৫')
                    && $seasons->contains('২৫-২৬');
            })
            ->set('seasonFilter', '২৩-২৪')
            ->assertSet('seasonFilter', '২৩-২৪')
            ->assertViewHas('challans', function ($challans) {
                return $challans->total() === 1
                    && $challans->first()->customer_name === 'Old Season Customer';
            })
            ->assertOk();

        Livewire::test(TodayCollection::class)
            ->set('seasonFilter', '২৫-২৬')
            ->assertOk();

        Livewire::test(DueToday::class)
            ->set('seasonFilter', '২৫-২৬')
            ->assertOk();
    }

    public function test_due_ledger_pages_follow_topbar_active_season()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Setting::set('season', '২৫-২৬');

        Challan::create([
            'customer_type' => 'old',
            'customer_name' => 'Active Season Customer',
            'customer_phone' => '01111111111',
            'challan_no' => '3',
            'date' => now()->toDateString(),
            'challan_type' => 'আজকের',
            'grand_total' => 500,
            'cash' => 0,
            'due' => 500,
            'due_payment_date' => now()->toDateString(),
            'season' => '২৫-২৬',
        ]);

        Challan::create([
            'customer_type' => 'old',
            'customer_name' => 'Other Season Customer',
            'customer_phone' => '02222222222',
            'challan_no' => '4',
            'date' => now()->toDateString(),
            'challan_type' => 'আজকের',
            'grand_total' => 500,
            'cash' => 0,
            'due' => 500,
            'due_payment_date' => now()->toDateString(),
            'season' => '২৩-২৪',
        ]);

        Livewire::test(AllDueList::class)
            ->assertSet('seasonFilter', '২৫-২৬')
            ->assertViewHas('challans', function ($challans) {
                $names = collect($challans->items())->pluck('customer_name')->all();
                return in_array('Active Season Customer', $names)
                    && !in_array('Other Season Customer', $names);
            });

        Livewire::test(TodayCollection::class)
            ->assertSet('seasonFilter', '২৫-২৬')
            ->assertOk();

        Livewire::test(DueToday::class)
            ->assertSet('seasonFilter', '২৫-২৬')
            ->assertOk();

        Setting::set('season', '২৩-২৪');

        Livewire::test(AllDueList::class)
            ->assertSet('seasonFilter', '২৩-২৪')
            ->assertViewHas('challans', function ($challans) {
                $names = collect($challans->items())->pluck('customer_name')->all();
                return in_array('Other Season Customer', $names)
                    && !in_array('Active Season Customer', $names);
            });
    }
}
