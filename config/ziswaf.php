<?php

return [
    // Id of the "Hak Amil" Book — a dedicated cross-fund-type book that pools the amil's
    // share cut from every confirmed donation and is charged for operational expenses.
    // Filled in after the book is seeded (see database/seeders/ZiswafSeeder.php).
    'hak_amil_book_id' => env('ZISWAF_HAK_AMIL_BOOK_ID'),

    // Default Hak Amil percentage seeded per fund-type Book name (via Setting::for($book)).
    // Admins can change these later per book through the Ziswaf settings UI.
    'default_hak_amil_percentages' => [
        'Zakat' => 12.5,
        'Infak' => 20,
        'Sedekah' => 20,
        'Wakaf' => 20,
    ],

    // Fixed list of the 8 Asnaf groups (golongan penerima zakat), seeded as Categories
    // in each fund-type Book. code|label, one per line.
    'asnaf' => [
        'fakir' => 'Fakir',
        'miskin' => 'Miskin',
        'amil' => 'Amil',
        'muallaf' => 'Muallaf',
        'riqab' => 'Riqab (Hamba Sahaya)',
        'gharimin' => 'Gharimin',
        'fisabilillah' => 'Fisabilillah',
        'ibnu_sabil' => 'Ibnu Sabil',
    ],

    // Operational expense categories seeded in the Hak Amil Book.
    'expense_categories' => [
        'air' => 'Air',
        'listrik' => 'Listrik',
        'atk' => 'ATK',
        'internet' => 'Internet',
        'lainnya' => 'Lainnya',
    ],

    // Payment method options for the donation form.
    'payment_methods' => [
        'transfer_bank' => 'Transfer Bank',
        'e_wallet' => 'E-Wallet',
        'tunai' => 'Tunai',
    ],
];
