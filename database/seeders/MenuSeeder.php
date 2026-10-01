<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\User;
use Illuminate\Database\Seeder;

/**
 * Recreates today's hardcoded public nav (see layouts/guest.blade.php) as Menu rows, so
 * switching the nav to render dynamically (docs/cms.md §4) doesn't leave the public site
 * empty. Run this BEFORE the guest layout is switched to the dynamic loop.
 *
 * Idempotent: safe to re-run (matches on label, updates the rest).
 */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $creatorId = optional(User::where('role_id', User::ROLE_ADMIN)->first())->id;

        $items = [
            ['label' => 'Beranda', 'target_type' => Menu::TARGET_URL, 'target_value' => '/', 'order' => 0],
            ['label' => 'Laporan', 'target_type' => Menu::TARGET_ROUTE, 'target_value' => 'public_reports.index', 'order' => 10],
            ['label' => 'Program', 'target_type' => Menu::TARGET_ROUTE, 'target_value' => 'public.books.index', 'order' => 20],
            ['label' => 'Jadwal Pengajian', 'target_type' => Menu::TARGET_ROUTE, 'target_value' => 'public_schedules.this_week', 'order' => 30],
            ['label' => 'Kontak', 'target_type' => Menu::TARGET_ROUTE, 'target_value' => 'public.contact', 'order' => 40],
        ];

        foreach ($items as $item) {
            Menu::updateOrCreate(
                ['location_code' => 'main_nav', 'label' => $item['label']],
                $item + ['is_active' => true, 'creator_id' => $creatorId]
            );
        }
    }
}
