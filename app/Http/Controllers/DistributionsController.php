<?php

namespace App\Http\Controllers;

use App\Http\Requests\Distributions\CreateRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\Distribution;
use Illuminate\Http\Request;

class DistributionsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', new Distribution);

        $distributionQuery = Distribution::query()->with(['book', 'category']);
        $distributionQuery->when($request->get('book_id'), function ($query, $bookId) {
            $query->where('book_id', $bookId);
        });
        $distributionQuery->when($request->get('status_id') !== null && $request->get('status_id') !== '', function ($query) use ($request) {
            $query->where('status_id', $request->get('status_id'));
        });

        $distributions = $distributionQuery->orderByDesc('distribution_date')->orderByDesc('created_at')->paginate(20)->withQueryString();
        $fundBooks = $this->getFundBookList();

        return view('distributions.index', compact('distributions', 'fundBooks'));
    }

    public function create()
    {
        $this->authorize('create', new Distribution);

        $fundBooks = $this->getFundBookList();
        // Asnaf categories grouped by book_id — the create form filters this list client-side
        // once a fund book is picked, since a distribution's book_id isn't known server-side yet.
        $asnafCategoriesByBook = Category::withoutGlobalScope('forActiveBook')
            ->whereIn('book_id', $fundBooks->keys())
            ->where('status_id', Category::STATUS_ACTIVE)
            ->orderBy('name')
            ->get()
            ->groupBy('book_id');
        // Available balance per fund book, shown next to the amount field so the operator
        // can see the ceiling before NotExceedBalance rejects the submission server-side.
        $bookBalances = Book::whereIn('id', $fundBooks->keys())->get()
            ->mapWithKeys(fn ($book) => [$book->id => $book->getBalance()]);

        return view('distributions.create', compact('fundBooks', 'asnafCategoriesByBook', 'bookBalances'));
    }

    public function store(CreateRequest $distributionCreateForm)
    {
        $distribution = $distributionCreateForm->save();
        flash(__('distribution.created'), 'success');

        return redirect()->route('distributions.show', $distribution);
    }

    public function show(Distribution $distribution)
    {
        $this->authorize('view', $distribution);

        return view('distributions.show', compact('distribution'));
    }

    public function destroy(Distribution $distribution)
    {
        $this->authorize('delete', $distribution);
        $this->authorize('manage-distributions', $distribution->book);

        $request = request();
        $request->validate(['distribution_id' => 'required']);

        if ($request->get('distribution_id') == $distribution->id) {
            $distribution->files->each->delete();
            $distribution->delete();
            flash(__('distribution.deleted'), 'warning');

            return redirect()->route('distributions.index');
        }

        flash(__('distribution.undeleted'), 'error');

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
