<?php

return [
    // Valid Menu::target_type values — see app/Models/Menu.php and docs/cms.md §3.3.
    'menu_target_types' => ['route', 'post', 'url'],

    // Default Menu::location_code for the public site's top navigation.
    'default_menu_location' => 'main_nav',

    // Reserved Post (type_code = page) slugs rendered inline on the homepage (see
    // guest/welcome.blade.php and docs/cms.md §5.2), seeded by HomepageContentSeeder.
    // PostPolicy/Posts\UpdateRequest guard these: the slug can't be changed and the
    // Post can't be deleted, so the homepage sections can never end up missing.
    'reserved_page_slugs' => [
        'sejarah' => 'Sejarah Masjid',
        'visi-misi' => 'Visi & Misi',
        'struktur-pengurus' => 'Struktur Pengurus',
    ],
];
