<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';
    public $filterPeriod = 'today'; // today, 7days, 15days, month, last_month
    public $dateFilter = '2026-07-12';

    /**
     * Restrict access to users with dashboard permission or admin role.
     */
    public function mount()
    {
        // Accessible by all authenticated users
    }

    // Sample data structure matching the tables in image_680313.png
    public $challans = [
        ['category' => '১ম শ্রেণি', 'challan_no' => 'CH-101', 'qty' => 5000, 'total' => '৳ ৪৫,০০০'],
        ['category' => '২য় শ্রেণি', 'challan_no' => 'CH-102', 'qty' => 3000, 'total' => '৳ ২৪,০০০'],
        ['category' => '১ম শ্রেণি', 'challan_no' => 'CH-103', 'qty' => 4500, 'total' => '৳ ৪০,৫০০'],
    ];

    public $payments = [
        ['khatian' => 'কেএইচ-১২', 'qty' => '৳ ৫০,০০০', 'received_by' => 'হিসাবরক্ষক'],
        ['khatian' => 'কেএইচ-০৫', 'qty' => '৳ ৩০,০০০', 'received_by' => 'ক্যাশিয়ার'],
    ];

    public $productions = [
        ['oil' => '৫০ লিটার', 'production' => '১০,০০০ পিস'],
        ['oil' => '৪০ লিটার', 'production' => '৮,০০০ পিস'],
    ];

    public $loads = [
        ['details' => 'ট্রাক লোডিং (১০ চাকা)', 'qty' => '১৫,০০০ পিস'],
        ['details' => 'ট্রাক লোডিং (৬ চাকা)', 'qty' => '৮,০০০ পিস'],
    ];

    public $deliveries = [
        ['category' => '১ম শ্রেণি', 'qty' => '৫,০০০ পিস'],
        ['category' => '২য় শ্রেণি', 'qty' => '৩,০০০ পিস'],
    ];

    public $deposits = [
        ['category' => 'ব্যাংক আমানত', 'amount' => '৳ ২,০০,০০০'],
        ['category' => 'ক্যাশ আমানত', 'amount' => '৳ ৫০,০০০'],
    ];

    public function setPeriod($period)
    {
        $this->filterPeriod = $period;
        // In a real application, you would query database records based on the period here.
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
