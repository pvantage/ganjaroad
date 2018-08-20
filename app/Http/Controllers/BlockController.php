<?php

namespace App\Http\Controllers;

use App\Block;
use App\Http\Requests;
use App\Http\Requests\BlockRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Sentinel;


class BlockController extends WeedController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $blocks = Block::all();

        return View('admin.block.index', compact('blocks'));
    }


    public function create()
    {
        return view('admin.block.create');
    }

    public function store(BlockRequest $request)
    {
        $block = new Block($request->except('image'));
        $block->user_id = Sentinel::getUser()->id;
        $block->save();

        if ($block->id) {
            return redirect('admin/block')->with('success', trans('block/message.success.create'));
        } else {
            return redirect('admin/block')->withInput()->with('error', trans('block/message.error.create'));
        }

    }

    public function show(Block $block)
    {
        return view('admin.block.show', compact('block'));
    }
	
    public function edit(Block $block)
    {
        return view('admin.block.edit', compact('block'));
    }

    public function update(BlockRequest $request, Block $block)
    {
        
        if ($block->update($request->except('image'))) {
            return redirect('admin/block')->with('success', trans('block/message.success.update'));
        } else {
            return redirect('admin/block')->withInput()->with('error', trans('block/message.error.update'));
        }
    }

    public function getModalDelete(Block $block)
    {
        $model = 'block';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/block', ['id' => $block->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('block/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(Block $block)
    {
        if ($block->delete()) {
            return redirect('admin/block')->with('success', trans('block/message.success.delete'));
        } else {
            return redirect('admin/block')->withInput()->with('error', trans('block/message.error.delete'));
        }
    }
}
