<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Livewire\Investment;
use App\Livewire\DocumentManager;
use App\Livewire\MalamalStock;
use Livewire\Livewire;

class NewModulesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_investment_page_renders_successfully()
    {
        $this->actingAs($this->user)
            ->get(route('investment'))
            ->assertStatus(200)
            ->assertSee('ইনভেস্টমেন্ট হিসাব');

        Livewire::test(Investment::class)
            ->set('investorName', 'হাজী আব্দুর রহিম')
            ->set('profitPercentage', '10')
            ->call('saveInvestor')
            ->assertSee('হাজী আব্দুর রহিম');
    }

    public function test_document_manager_renders_successfully()
    {
        $this->actingAs($this->user)
            ->get(route('documents'))
            ->assertStatus(200)
            ->assertSee('Home');

        Livewire::test(DocumentManager::class)
            ->set('folderName', 'জরুরী ভাউচার')
            ->call('saveFolder')
            ->assertSee('জরুরী ভাউচার');
    }

    public function test_malamal_stock_renders_successfully()
    {
        $this->actingAs($this->user)
            ->get(route('malamal-stock'))
            ->assertStatus(200)
            ->assertSee('অ্যাসেট ম্যানেজমেন্ট');

        Livewire::test(MalamalStock::class)
            ->set('assetName', 'ওয়াটার মোটর')
            ->set('unitPrice', '5000')
            ->set('initialQty', '2')
            ->call('saveAsset')
            ->assertSee('ওয়াটার মোটর');
    }
}
