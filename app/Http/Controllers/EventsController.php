<?php

namespace App\Http\Controllers;

use App\Http\Requests\Events\CreateRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', new Event);

        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $eventQuery = Event::query()->with('book');
        $eventQuery->whereYear('start_date', $year)->whereMonth('start_date', $month);
        $events = $eventQuery->orderBy('start_date')->get();

        return view('events.index', compact('events', 'year', 'month'));
    }

    public function create()
    {
        $this->authorize('create', new Event);

        $books = $this->getBookList();

        return view('events.create', compact('books'));
    }

    public function store(CreateRequest $eventCreateForm)
    {
        $event = $eventCreateForm->save();
        flash(__('event.created'), 'success');

        return redirect()->route('events.show', $event);
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $books = $this->getBookList();

        return view('events.edit', compact('event', 'books'));
    }

    public function update(UpdateRequest $eventUpdateForm, Event $event)
    {
        $event = $eventUpdateForm->save();
        flash(__('event.updated'), 'success');

        return redirect()->route('events.show', $event);
    }

    public function destroy(Request $request, Event $event)
    {
        $this->authorize('delete', $event);

        $request->validate(['event_id' => 'required']);

        if ($request->get('event_id') == $event->id && $event->delete()) {
            flash(__('event.deleted'), 'success');

            return redirect()->route('events.index');
        }

        flash(__('event.undeleted'), 'error');

        return back();
    }
}
