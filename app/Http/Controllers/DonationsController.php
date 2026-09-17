<?php

namespace App\Http\Controllers;

use App\Http\Requests\Donations\CreateRequest;
use App\Models\Book;
use App\Models\Donation;
use App\Models\Partner;
use Illuminate\Http\Request;

class DonationsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', new Donation);

        $donationQuery = Donation::query()->with(['partner', 'book']);
        $donationQuery->when($request->get('book_id'), function ($query, $bookId) {
            $query->where('book_id', $bookId);
        });
        $donationQuery->when($request->get('status_id') !== null && $request->get('status_id') !== '', function ($query) use ($request) {
            $query->where('status_id', $request->get('status_id'));
        });

        $donations = $donationQuery->orderByDesc('date')->orderByDesc('created_at')->paginate(20)->withQueryString();
        $fundBooks = $this->getFundBookList();

        return view('donations.index', compact('donations', 'fundBooks'));
    }

    public function create()
    {
        $this->authorize('create', new Donation);

        $fundBooks = $this->getFundBookList();
        $paymentMethods = config('ziswaf.payment_methods');
        // name_phone is an Eloquent accessor, not a column — pluck() needs a hydrated
        // Collection (via get()) to resolve it, not the query builder.
        $muzakkiPartners = Partner::where('is_active', Partner::STATUS_ACTIVE)
            ->whereJsonContains('type_code', 'muzakki')
            ->orderBy('name')
            ->get()
            ->pluck('name_phone', 'id');

        return view('donations.create', compact('fundBooks', 'paymentMethods', 'muzakkiPartners'));
    }

    public function store(CreateRequest $donationCreateForm)
    {
        $donation = $donationCreateForm->save();
        flash(__('donation.created'), 'success');

        return redirect()->route('donations.show', $donation);
    }

    public function show(Donation $donation)
    {
        $this->authorize('view', $donation);

        return view('donations.show', compact('donation'));
    }

    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);
        $this->authorize('manage-donations', $donation->book);

        $request = request();
        $request->validate(['donation_id' => 'required']);

        if ($request->get('donation_id') == $donation->id) {
            $donation->files->each->delete();
            $donation->delete();
            flash(__('donation.deleted'), 'warning');

            return redirect()->route('donations.index');
        }

        flash(__('donation.undeleted'), 'error');

        return back();
    }

    private function getFundBookList()
    {
        return Book::whereNotIn('id', array_filter([config('ziswaf.hak_amil_book_id')]))
            ->where('status_id', Book::STATUS_ACTIVE)
            ->orderBy('name')
            ->pluck('name', 'id');
    }
}
