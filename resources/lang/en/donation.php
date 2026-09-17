<?php

return [
    // Labels
    'donation' => 'Donation',
    'list' => 'Donation List',
    'detail' => 'Donation Detail',
    'id' => 'Donation ID',
    'not_found' => 'No donations yet.',
    'back_to_index' => 'Back to donation list',
    'anonymous' => 'Anonymous',

    // Actions
    'create' => 'Record New Donation',
    'created' => 'New donation recorded, awaiting confirmation.',
    'confirm' => 'Confirm Donation',
    'confirm_confirm' => 'Confirm this donation? The amount will be automatically split into a net fund and Hak Amil.',
    'confirmed' => 'Donation confirmed and recorded as a transaction.',
    'delete_confirm' => 'Are you sure you want to delete this donation?',
    'deleted' => 'Donation deleted.',
    'undeleted' => 'Failed to delete donation.',

    // Attributes
    'fund_book' => 'Fund Type',
    'muzakki' => 'Muzakki',
    'date' => 'Donation Date',
    'amount' => 'Donation Amount',
    'payment_method' => 'Payment Method',
    'proof' => 'Payment Proof',
    'upload_proof' => 'Upload Payment Proof',
    'net_amount' => 'Net Amount',
    'hak_amil_amount' => 'Hak Amil',
    'confirmed_at' => 'Confirmed at',

    // Statuses
    'status_pending' => 'Awaiting Confirmation',
    'status_confirmed' => 'Confirmed',
    'status_failed' => 'Failed',

    // Filters
    'filter_book' => 'All Fund Types',
    'filter_status' => 'All Statuses',

    // Internal transaction descriptions (see Donations\ConfirmRequest)
    'net_transaction_description' => 'Donation #:id',
    'hak_amil_transaction_description' => 'Hak Amil from donation #:id',
];
