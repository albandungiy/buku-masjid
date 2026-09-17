<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Partner;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Setting;

/**
 * Fills in demo/dummy content that database/seeders/ZiswafSeeder.php and the
 * buku-masjid/demo-data package (php artisan buku-masjid:generate-demo-data) don't
 * cover: Partners (muzakki/donatur) and the full Ziswaf flow (Donations in every
 * status, Distributions in every status, Events).
 *
 * Not a default seeder — run it explicitly:
 *   php artisan db:seed --class=DemoContentSeeder
 *
 * Every row this seeder creates has created_at/updated_at left NULL, matching the
 * existing "demo data" convention in this codebase (see vendor/buku-masjid/demo-data's
 * RemoveDemoData, which deletes on ->whereNull('created_at')). Remove everything this
 * seeder created with:
 *   php artisan buku-masjid:remove-ziswaf-demo-data
 */
class DemoContentSeeder extends Seeder
{
    private User $creator;

    public function run(): void
    {
        config(['app.faker_locale' => 'id_ID']);

        $this->creator = User::where('role_id', User::ROLE_FINANCE)->where('is_active', 1)->first()
            ?? User::where('role_id', User::ROLE_ADMIN)->first();

        if (!$this->creator) {
            $this->command?->warn('No user found to attribute demo data to — run DefaultUserTableSeeder first.');

            return;
        }

        DB::transaction(function () {
            $partners = $this->seedPartners();

            if (config('features.ziswaf.is_active')) {
                $this->seedDonationsAndDistributions($partners);
                $this->seedEvents();
            } else {
                $this->command?->warn('features.ziswaf.is_active is off — skipped donations/distributions/events.');
            }
        });

        $this->command?->info('Demo content generated. Remove it later with: php artisan buku-masjid:remove-ziswaf-demo-data');
    }

    /**
     * Save $model with created_at/updated_at left NULL, marking it as demo data —
     * the same signal vendor/buku-masjid/demo-data uses for its own generated rows.
     */
    private function markAsDemo(Model $model): Model
    {
        $model->timestamps = false;
        $model->created_at = null;
        $model->updated_at = null;
        $model->save();

        return $model;
    }

    private function seedPartners(): array
    {
        $muzakkiNames = ['Ahmad Fauzi', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari', 'Muhammad Rizki', 'Nur Aini'];
        $donaturNames = ['Hj. Aminah', 'H. Sulaiman', 'Rina Wulandari', 'Agus Salim', 'Yusuf Kartawijaya'];
        $femaleNames = ['Siti Nurhaliza', 'Dewi Lestari', 'Nur Aini', 'Hj. Aminah', 'Rina Wulandari'];

        $createPartner = function (string $name, string $typeCode) use ($femaleNames) {
            return $this->markAsDemo(Partner::create([
                'name' => $name,
                'type_code' => [$typeCode],
                'gender_code' => in_array($name, $femaleNames) ? 'f' : 'm',
                'phone' => '08'.rand(1111111111, 9999999999),
                'address' => fake()->address(),
                'is_active' => Partner::STATUS_ACTIVE,
                'creator_id' => $this->creator->id,
            ]));
        };

        return [
            'muzakki' => array_map(fn ($name) => $createPartner($name, 'muzakki'), $muzakkiNames),
            'donatur' => array_map(fn ($name) => $createPartner($name, 'donatur'), $donaturNames),
        ];
    }

    private function seedDonationsAndDistributions(array $partners): void
    {
        $hakAmilBookId = config('ziswaf.hak_amil_book_id');
        $hakAmilBook = $hakAmilBookId ? Book::find($hakAmilBookId) : null;
        if (!$hakAmilBook) {
            $this->command?->warn('ZISWAF_HAK_AMIL_BOOK_ID not configured — skipped donations/distributions.');

            return;
        }

        $fundBooks = Book::ziswafFundBooks()->get();
        if ($fundBooks->isEmpty()) {
            $this->command?->warn('No Ziswaf fund books found — run ZiswafSeeder first.');

            return;
        }

        $muzakkiPool = $partners['muzakki'];
        $distributionTitles = [
            'Bantuan sembako', 'Santunan yatim piatu', 'Bantuan biaya sekolah', 'Bantuan pengobatan',
            'Bantuan modal usaha', 'Renovasi rumah dhuafa',
        ];
        $amountChoices = [50000, 100000, 150000, 250000, 500000, 1000000, 2000000];

        foreach ($fundBooks as $fundBook) {
            $percentage = (float) Setting::for($fundBook)->get('hak_amil_percentage', 0);
            $asnafCategories = Category::withoutGlobalScope('forActiveBook')->where('book_id', $fundBook->id)->get();

            // Confirmed donations, spread over the last ~60 days — same split logic as
            // Donations\ConfirmRequest::save(), replicated here since we're seeding
            // already-confirmed history rather than going through the HTTP flow.
            for ($i = 0, $count = rand(5, 8); $i < $count; $i++) {
                $amount = $amountChoices[array_rand($amountChoices)];
                $date = now()->subDays(rand(1, 60))->format('Y-m-d');
                $partner = rand(0, 4) === 0 ? null : $muzakkiPool[array_rand($muzakkiPool)];

                $donation = $this->markAsDemo(Donation::create([
                    'partner_id' => optional($partner)->id,
                    'book_id' => $fundBook->id,
                    'date' => $date,
                    'amount' => $amount,
                    'payment_method_code' => array_rand(config('ziswaf.payment_methods')),
                    'status_id' => Donation::STATUS_PENDING,
                    'creator_id' => $this->creator->id,
                ]));

                $hakAmilAmount = round($amount * $percentage / 100, 2);
                $netAmount = $amount - $hakAmilAmount;

                $netTransaction = $this->markAsDemo(Transaction::create([
                    'date' => $date, 'amount' => $netAmount, 'in_out' => Transaction::TYPE_INCOME,
                    'description' => __('donation.net_transaction_description', ['id' => $donation->id]),
                    'partner_id' => optional($partner)->id, 'book_id' => $fundBook->id, 'creator_id' => $this->creator->id,
                ]));

                $hakAmilTransaction = $hakAmilAmount > 0 ? $this->markAsDemo(Transaction::create([
                    'date' => $date, 'amount' => $hakAmilAmount, 'in_out' => Transaction::TYPE_INCOME,
                    'description' => __('donation.hak_amil_transaction_description', ['id' => $donation->id]),
                    'partner_id' => optional($partner)->id, 'book_id' => $hakAmilBook->id, 'creator_id' => $this->creator->id,
                ])) : null;

                $donation->timestamps = false;
                $donation->update([
                    'status_id' => Donation::STATUS_CONFIRMED,
                    'net_transaction_id' => $netTransaction->id,
                    'hak_amil_transaction_id' => optional($hakAmilTransaction)->id,
                    'confirmed_at' => $date,
                ]);
            }

            // A few still-pending donations awaiting confirmation.
            for ($i = 0, $count = rand(1, 3); $i < $count; $i++) {
                $partner = rand(0, 2) === 0 ? null : $muzakkiPool[array_rand($muzakkiPool)];
                $this->markAsDemo(Donation::create([
                    'partner_id' => optional($partner)->id,
                    'book_id' => $fundBook->id,
                    'date' => now()->subDays(rand(0, 5))->format('Y-m-d'),
                    'amount' => $amountChoices[array_rand(array_slice($amountChoices, 0, 3))],
                    'payment_method_code' => array_rand(config('ziswaf.payment_methods')),
                    'status_id' => Donation::STATUS_PENDING,
                    'creator_id' => $this->creator->id,
                ]));
            }

            // One failed donation, for a status the UI should also be able to show.
            $this->markAsDemo(Donation::create([
                'partner_id' => null,
                'book_id' => $fundBook->id,
                'date' => now()->subDays(rand(5, 20))->format('Y-m-d'),
                'amount' => 75000,
                'payment_method_code' => Donation::PAYMENT_METHOD_TRANSFER_BANK,
                'status_id' => Donation::STATUS_FAILED,
                'creator_id' => $this->creator->id,
            ]));

            // Distributions: track the running balance so approved ones never overdraw
            // the book, mirroring what Distributions\ApproveRequest enforces for real.
            $availableBalance = $fundBook->getBalance();

            for ($i = 0, $count = rand(2, 3); $i < $count && $availableBalance > 50000; $i++) {
                $amount = min($availableBalance, $amountChoices[array_rand(array_slice($amountChoices, 0, 4))]);
                $availableBalance -= $amount;
                $date = now()->subDays(rand(1, 45))->format('Y-m-d');

                $distribution = $this->markAsDemo(Distribution::create([
                    'book_id' => $fundBook->id,
                    'category_id' => $asnafCategories->random()->id,
                    'title' => $distributionTitles[array_rand($distributionTitles)],
                    'amount' => $amount,
                    'distribution_date' => $date,
                    'creator_id' => $this->creator->id,
                    'status_id' => Distribution::STATUS_PENDING,
                ]));

                $transaction = $this->markAsDemo(Transaction::create([
                    'date' => $date, 'amount' => $amount, 'in_out' => Transaction::TYPE_SPENDING,
                    'description' => $distribution->title, 'category_id' => $distribution->category_id,
                    'book_id' => $fundBook->id, 'creator_id' => $this->creator->id,
                ]));

                $distribution->timestamps = false;
                $distribution->update([
                    'status_id' => Distribution::STATUS_APPROVED,
                    'transaction_id' => $transaction->id,
                    'approved_id' => $this->creator->id,
                    'approved_at' => $date,
                ]);
            }

            if ($availableBalance > 50000) {
                $this->markAsDemo(Distribution::create([
                    'book_id' => $fundBook->id,
                    'category_id' => $asnafCategories->random()->id,
                    'title' => $distributionTitles[array_rand($distributionTitles)],
                    'amount' => min($availableBalance, 100000),
                    'distribution_date' => now()->format('Y-m-d'),
                    'creator_id' => $this->creator->id,
                    'status_id' => Distribution::STATUS_PENDING,
                ]));
            }

            $this->markAsDemo(Distribution::create([
                'book_id' => $fundBook->id,
                'category_id' => $asnafCategories->random()->id,
                'title' => $distributionTitles[array_rand($distributionTitles)],
                'amount' => 50000,
                'distribution_date' => now()->subDays(rand(5, 15))->format('Y-m-d'),
                'creator_id' => $this->creator->id,
                'status_id' => Distribution::STATUS_REJECTED,
            ]));
        }
    }

    private function seedEvents(): void
    {
        $events = [
            ['title' => 'Rapat Pengurus UPZ', 'location' => 'Aula Masjid', 'days_offset' => -10],
            ['title' => 'Kajian Zakat & Wakaf', 'location' => 'Ruang Utama Masjid', 'days_offset' => -3],
            ['title' => 'Pembagian Zakat Fitrah', 'location' => 'Halaman Masjid', 'days_offset' => 5],
            ['title' => 'Santunan Yatim Piatu', 'location' => 'Aula Masjid', 'days_offset' => 12],
            ['title' => 'Rapat Evaluasi Bulanan', 'location' => 'Ruang Pengurus', 'days_offset' => 20],
            ['title' => 'Sosialisasi Program Wakaf Produktif', 'location' => 'Aula Masjid', 'days_offset' => 30],
        ];
        $colors = ['primary', 'success', 'warning', 'danger', 'info'];

        foreach ($events as $event) {
            $this->markAsDemo(Event::create([
                'title' => $event['title'],
                'location' => $event['location'],
                'description' => 'Kegiatan demo untuk keperluan simulasi tampilan.',
                'start_date' => now()->addDays($event['days_offset'])->setTime(8, 0),
                'color_code' => $colors[array_rand($colors)],
                'creator_id' => $this->creator->id,
            ]));
        }
    }
}
