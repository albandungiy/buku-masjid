<?php

namespace App\Http\Livewire\PublicHome;

use App\Models\Book;
use Livewire\Component;

class ZiswafSummary extends Component
{
    public $fundBooks;

    public function mount()
    {
        $this->fundBooks = collect();

        if (!config('features.ziswaf.is_active')) {
            return;
        }

        // Only publicly-visible fund books are shown, respecting the same
        // report_visibility_code control admins already use for every other book.
        $this->fundBooks = Book::ziswafFundBooks()
            ->where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->get()
            ->map(function ($book) {
                $book->balance = $book->getBalance();

                return $book;
            });
    }

    public function render()
    {
        return view('livewire.public_home.ziswaf_summary');
    }
}
