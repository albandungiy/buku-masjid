<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunningTexts\CreateRequest;
use App\Http\Requests\RunningTexts\DeleteRequest;
use App\Http\Requests\RunningTexts\ReorderRequest;
use App\Http\Requests\RunningTexts\UpdateRequest;
use App\Models\RunningText;

class RunningTextsController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', new RunningText);

        $editableRunningText = null;
        $runningTexts = RunningText::orderBy('order')->get();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableRunningText = RunningText::find(request('id'));
        }

        return view('running_texts.index', compact('runningTexts', 'editableRunningText'));
    }

    public function store(CreateRequest $runningTextCreateForm)
    {
        $runningTextCreateForm->save();
        flash(__('running_text.created'), 'success');

        return redirect()->route('running_texts.index');
    }

    public function update(UpdateRequest $runningTextUpdateForm, RunningText $running_text)
    {
        $runningTextUpdateForm->save();
        flash(__('running_text.updated'), 'success');

        return redirect()->route('running_texts.index');
    }

    public function destroy(DeleteRequest $runningTextDeleteForm, RunningText $running_text)
    {
        if ($runningTextDeleteForm->delete()) {
            flash(__('running_text.deleted'), 'warning');

            return redirect()->route('running_texts.index');
        }

        flash(__('running_text.undeleted'), 'warning');

        return back();
    }

    public function reorder(ReorderRequest $runningTextReorderForm)
    {
        $runningTextReorderForm->save();

        return response()->json(['success' => true]);
    }
}
