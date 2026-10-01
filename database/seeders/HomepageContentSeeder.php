<?php

namespace Database\Seeders;

use App\Models\Post;
use App\User;
use Illuminate\Database\Seeder;

/**
 * Seeds the 3 reserved homepage-section Pages (config('cms.reserved_page_slugs'), see
 * docs/cms.md §5.2/§5.3) with dummy content, so the redesigned homepage isn't empty
 * before an admin writes the real Sejarah/Visi Misi/Struktur Pengurus content.
 *
 * Not a default seeder — run explicitly:
 *   php artisan db:seed --class=HomepageContentSeeder
 *
 * Idempotent: uses updateOrCreate on slug, safe to re-run.
 */
class HomepageContentSeeder extends Seeder
{
    public function run(): void
    {
        $creatorId = optional(User::where('role_id', User::ROLE_ADMIN)->first())->id;

        $pages = [
            'sejarah' => [
                'excerpt' => 'Perjalanan berdirinya masjid dari sebuah musala kecil hingga menjadi pusat kegiatan umat seperti sekarang.',
                'content' => '<p>Masjid ini didirikan pada tahun 1985 oleh sekelompok warga yang berinisiatif membangun tempat ibadah di lingkungan yang saat itu belum memiliki musala permanen. Bermula dari bangunan sederhana berukuran 6x8 meter, masjid ini dibangun secara gotong royong oleh warga sekitar.</p>'
                    .'<p>Seiring bertambahnya jumlah jamaah, pada tahun 1998 dilakukan renovasi dan perluasan bangunan menjadi dua lantai untuk menampung jamaah yang terus bertambah, terutama saat shalat Jumat dan bulan Ramadhan.</p>'
                    .'<p>Pada tahun 2015, masjid kembali direnovasi dengan penambahan fasilitas seperti tempat wudhu yang lebih luas, ruang perpustakaan, dan aula serbaguna untuk kegiatan pendidikan dan sosial kemasyarakatan. Hingga kini, masjid terus berkembang menjadi pusat kegiatan ibadah, dakwah, dan sosial bagi warga sekitar.</p>',
            ],
            'visi-misi' => [
                'excerpt' => 'Visi dan misi pengelolaan masjid sebagai pusat ibadah, dakwah, dan pemberdayaan umat.',
                'content' => '<h4>Visi</h4>'
                    .'<p>Menjadi masjid yang makmur, mandiri, dan menjadi pusat pembinaan umat yang berakhlak mulia serta bermanfaat bagi masyarakat sekitar.</p>'
                    .'<h4>Misi</h4>'
                    .'<ul>'
                    .'<li>Menyelenggarakan ibadah shalat lima waktu, Jumat, dan hari besar Islam secara tertib dan khusyuk.</li>'
                    .'<li>Menyelenggarakan kajian rutin dan pendidikan keagamaan bagi seluruh lapisan usia jamaah.</li>'
                    .'<li>Mengelola dana Ziswaf (Zakat, Infak, Sedekah, Wakaf) secara transparan dan akuntabel.</li>'
                    .'<li>Menjadi pusat kegiatan sosial kemasyarakatan yang bermanfaat bagi warga sekitar.</li>'
                    .'<li>Menjalin ukhuwah islamiyah antar jamaah dan masyarakat sekitar masjid.</li>'
                    .'</ul>',
            ],
            'struktur-pengurus' => [
                'excerpt' => 'Susunan pengurus takmir masjid periode berjalan.',
                'content' => '<table class="table table-bordered">'
                    .'<thead><tr><th>Jabatan</th><th>Nama</th></tr></thead>'
                    .'<tbody>'
                    .'<tr><td>Ketua Takmir</td><td>H. Ahmad Sudrajat</td></tr>'
                    .'<tr><td>Wakil Ketua</td><td>H. Bambang Supriyadi</td></tr>'
                    .'<tr><td>Sekretaris</td><td>Muhammad Rizal, S.Pd.</td></tr>'
                    .'<tr><td>Bendahara</td><td>Hj. Siti Aminah</td></tr>'
                    .'<tr><td>Koordinator Bidang Dakwah</td><td>Ustadz Fauzan Hakim</td></tr>'
                    .'<tr><td>Koordinator Bidang Pendidikan</td><td>Dewi Kartika, S.Ag.</td></tr>'
                    .'<tr><td>Koordinator Bidang Sosial & Ziswaf</td><td>Agus Salim</td></tr>'
                    .'</tbody>'
                    .'</table>',
            ],
        ];

        foreach ($pages as $slug => $data) {
            Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'type_code' => Post::TYPE_PAGE,
                    'title' => config('cms.reserved_page_slugs.'.$slug, ucfirst($slug)),
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'status_id' => Post::STATUS_PUBLISHED,
                    'published_at' => now(),
                    'creator_id' => $creatorId,
                ]
            );
        }
    }
}
