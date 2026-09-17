<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\User;
use Illuminate\Database\Seeder;
use Setting;

class ZiswafSeeder extends Seeder
{
    /**
     * Seeds one Book per Ziswaf fund type (Zakat, Infak, Sedekah, Wakaf) plus a dedicated
     * "Hak Amil" Book, sets the default Hak Amil percentage per fund-type Book, and seeds
     * the fixed Asnaf categories (per fund-type book) and expense categories (Hak Amil book).
     *
     * Values are sourced from config/ziswaf.php so this seeder stays in sync with the
     * documented defaults in docs/masjid.md.
     */
    public function run(): void
    {
        $financeUser = User::where('role_id', User::ROLE_FINANCE)->where('is_active', 1)->first();
        $creatorId = optional($financeUser)->id;

        $hakAmilBook = Book::create([
            'name' => 'Hak Amil',
            'description' => 'Kas amil: potongan hak amil dari seluruh jenis dana Ziswaf, dipakai untuk pengeluaran operasional.',
            'creator_id' => null,
            'manager_id' => $creatorId,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);

        foreach (config('ziswaf.expense_categories') as $code => $label) {
            Category::create([
                'name' => $label,
                'color' => config('masjid.spending_color', '#F16867'),
                'report_visibility_code' => Category::REPORT_VISIBILITY_INTERNAL,
                'creator_id' => $creatorId,
                'book_id' => $hakAmilBook->id,
            ]);
        }

        foreach (config('ziswaf.default_hak_amil_percentages') as $fundName => $percentage) {
            $fundBook = Book::create([
                'name' => $fundName,
                'description' => "Buku catatan dana {$fundName}",
                'creator_id' => null,
                'manager_id' => $creatorId,
                'report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
            ]);

            Setting::for($fundBook)->set('hak_amil_percentage', (string) $percentage);

            foreach (config('ziswaf.asnaf') as $asnafLabel) {
                Category::create([
                    'name' => $asnafLabel,
                    'color' => config('masjid.income_color', '#00AABB'),
                    'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC,
                    'creator_id' => $creatorId,
                    'book_id' => $fundBook->id,
                ]);
            }
        }

        $this->command?->info(
            "Ziswaf: set ZISWAF_HAK_AMIL_BOOK_ID={$hakAmilBook->id} in .env to finish setup."
        );
    }
}
