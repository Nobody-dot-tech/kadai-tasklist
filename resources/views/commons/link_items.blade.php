@if (Auth::check())
    {{-- 新規タスク追加ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('tasks.create') }}">新規タスクの追加</a></li>
    {{-- タスク一覧ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('tasks.show', Auth::user()->id }}">{{ Auth::user()->name }}のタスク一覧</a></li>
    {{-- ログアウトへのリンク --}}
    <li><a class="link link-hover" href="#" onclick="event.preventDefault();this.closest('form').submit();">ログアウト</a></li>
@else
    {{-- ユーザー登録ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('register') }}">ユーザー登録</a></li>
    <!-- <li class="divider lg:hidden"></li> -->
    {{-- ログインページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('login') }}">ログイン</a></li>
@endif