<?php

namespace App\Http\Controllers;

use App\Http\Requests\Menus\CreateRequest;
use App\Http\Requests\Menus\ReorderRequest;
use App\Http\Requests\Menus\UpdateRequest;
use App\Models\Menu;
use App\Models\Post;

class MenusController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', new Menu);

        $editableMenu = null;
        $menus = Menu::where('location_code', config('cms.default_menu_location'))
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with('children')
            ->get();
        $targetTypes = array_combine(config('cms.menu_target_types'), config('cms.menu_target_types'));
        $posts = Post::published()->orderBy('title')->pluck('title', 'id');

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableMenu = Menu::find(request('id'));
        }

        return view('menus.index', compact('menus', 'editableMenu', 'targetTypes', 'posts'));
    }

    public function store(CreateRequest $menuCreateForm)
    {
        $menuCreateForm->save();
        flash(__('menu.created'), 'success');

        return redirect()->route('menus.index');
    }

    public function update(UpdateRequest $menuUpdateForm, Menu $menu)
    {
        $menuUpdateForm->save();
        flash(__('menu.updated'), 'success');

        return redirect()->route('menus.index');
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);

        request()->validate(['menu_id' => 'required']);

        if (request('menu_id') == $menu->id && $menu->delete()) {
            flash(__('menu.deleted'), 'warning');

            return redirect()->route('menus.index');
        }

        flash(__('menu.undeleted'), 'error');

        return back();
    }

    public function reorder(ReorderRequest $menuReorderForm)
    {
        $menuReorderForm->save();

        return response()->json(['success' => true]);
    }
}
