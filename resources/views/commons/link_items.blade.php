@if (Auth::check())
    {{-- タスク一覧ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('tasks.index') }}">{{ Auth::user()->name }}のタスク一覧</a></li>
    {{-- ログアウトへのリンク --}}
    <li><a class="link link-hover" href="#" onclick="event.preventDefault();this.closest('form').submit();">ログアウト</a></li>
@else
    {{-- ユーザー登録ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('register') }}">ユーザー登録</a></li>
    {{-- ログインページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('login') }}">ログイン</a></li>
@endif