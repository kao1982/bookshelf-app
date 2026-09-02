<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReadingPlanRequest;
use App\Models\ReadingPlan;
use App\Models\Book;

class ReadingPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = ReadingPlan::where('user_id', auth()->id())
            ->with('book');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $readingPlans = $query
            ->orderBy('target_date')
            ->paginate(10)
            ->withQueryString();

        $currentStatus = $request->status;

        return view('reading-plans.index', compact('readingPlans', 'currentStatus'));
    }

    public function create()
    {
        $books = Book::orderBy('title')->get();

        return view('reading-plans.create', compact('books'));
    }
    public function store(ReadingPlanRequest $request)
    {
        ReadingPlan::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'target_date' => $request->target_date,
            'status' => \App\Enums\ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を登録しました。');
    }
    public function complete(ReadingPlan $readingPlan)
    {
        $this->authorize('complete', $readingPlan);

        $readingPlan->update([
            'status' => \App\Enums\ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を完了しました。');
    }
    public function edit(ReadingPlan $readingPlan)
    {
        $this->authorize('update', $readingPlan);

        $books = Book::orderBy('title')->get();

        return view('reading-plans.edit', compact('readingPlan', 'books'));
    }
    public function update(ReadingPlanRequest $request, ReadingPlan $readingPlan)
    {
        $this->authorize('update', $readingPlan);

        $readingPlan->update([
            'target_date' => $request->target_date,
        ]);

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を更新しました。');
    }
    public function destroy(ReadingPlan $readingPlan)
    {
        $this->authorize('delete', $readingPlan);

        $readingPlan->delete();

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を削除しました。');
    }
}
