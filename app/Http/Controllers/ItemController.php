<?php

namespace App\Http\Controllers;

use App\Enums\ItemStatus;
use App\Enums\Permission;
use App\Http\Requests\ItemRequest;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        $items = $this->itemRepository->getAllAvailable();
        return view('item_management.index', compact('items'));
    }

    public function create()
    {
        $statuses = ItemStatus::cases();
        return view('item_management.create', compact('statuses'));
    }


    public function store(ItemRequest $request)
    {
        $this->itemRepository->create($request->validated());
        return redirect()->route('item-management.index')->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {
        $item = $this->itemRepository->find($id);
        $statuses = ItemStatus::cases();
        return view('item_management.edit', compact('item', 'statuses'));
    }

    public function update(ItemRequest $request, $id)
    {
        $this->itemRepository->update($id, $request->validated());
        return redirect()->route('item-management.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $this->itemRepository->delete($id);
        return redirect()->route('item-management.index')->with('success', 'Item deleted successfully.');
    }
}
