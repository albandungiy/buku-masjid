<?php

return [
    // Labels
    'donation' => 'Donasi',
    'list' => 'Daftar Donasi',
    'detail' => 'Detail Donasi',
    'id' => 'ID Donasi',
    'not_found' => 'Belum ada Donasi.',
    'back_to_index' => 'Kembali ke daftar Donasi',
    'anonymous' => 'Hamba Allah',

    // Actions
    'create' => 'Catat Donasi Baru',
    'created' => 'Donasi baru telah dicatat, menunggu konfirmasi.',
    'confirm' => 'Konfirmasi Donasi',
    'confirm_confirm' => 'Konfirmasi donasi ini? Dana akan dipecah otomatis menjadi dana bersih dan Hak Amil.',
    'confirmed' => 'Donasi telah dikonfirmasi dan dicatat sebagai transaksi.',
    'delete_confirm' => 'Anda yakin akan menghapus Donasi ini?',
    'deleted' => 'Donasi telah dihapus.',
    'undeleted' => 'Donasi gagal dihapus.',

    // Attributes
    'fund_book' => 'Jenis Dana',
    'muzakki' => 'Muzakki',
    'date' => 'Tanggal Donasi',
    'amount' => 'Jumlah Donasi',
    'payment_method' => 'Metode Pembayaran',
    'proof' => 'Bukti Pembayaran',
    'upload_proof' => 'Unggah Bukti Pembayaran',
    'net_amount' => 'Dana Bersih',
    'hak_amil_amount' => 'Hak Amil',
    'confirmed_at' => 'Dikonfirmasi pada',

    // Statuses
    'status_pending' => 'Menunggu Konfirmasi',
    'status_confirmed' => 'Terkonfirmasi',
    'status_failed' => 'Gagal',

    // Filters
    'filter_book' => 'Semua Jenis Dana',
    'filter_status' => 'Semua Status',

    // Internal transaction descriptions (see Donations\ConfirmRequest)
    'net_transaction_description' => 'Donasi #:id',
    'hak_amil_transaction_description' => 'Hak Amil dari donasi #:id',
];
