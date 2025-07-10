<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        if (Auth::check()) {
            // タスク一覧を取得
            $user = \Auth::user();
            // ユーザーのタスク一覧を作成日時の降順で取得
            // 後で他ユーザーのタスクも取得するように変更する
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // ダッシュボードビューでそれを表示
        return view('/dashboard', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! Auth::check()) {
            return redirect()->route('/dashboard');
        }

        $task = new Task;

        // タスク追加ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('/dashboard');
        }

        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
            // 'user_id' => 'required',
        ]);

        // 認証済みユーザーの投稿として作成（リクエストされた値を基に作成）
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
            // 'user_id' => $request->user_id,
        ]);
        // タスクを追加
        // $task = new Task;
        // $task->status = $request->status;
        // $task->content = $request->content;
        // $task->user_id = $request->user_id;
        // $task->save();

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (! Auth::check()) {
            return redirect()->route('/dashboard');
        }

        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);

        if (Auth::id() !== $task->user_id) {
            return redirect()->route('dashboard');
        }
            // タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (! Auth::check()) {
            return redirect()->route('/dashboard');
        }

        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);

        if (Auth::id() !== $task->user_id) {
            return redirect()->route('dashboard');
        }

        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (! Auth::check()) {
            return redirect()->route('/dashboard');
        }

        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
        ]);
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);

        if (Auth::id() !== $task->user_id) {
            return redirect()->route('dashboard');
        }
        // タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (! Auth::check()) {
            return redirect()->route('/dashboard');
        }

        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合はタスクを削除
        if (Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/dashboard')
                ->with('success', 'Delete Successful');
        } else {
            // 前のページへリダイレクトさせる
            return redirect('/dashboard')->with('Delete Failed');
        }
    }
}
