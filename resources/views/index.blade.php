@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {{ Auth::user()->name }}
        <h1>タスク一覧</h1>
         {{ $tasks->links() }}
         {!! link_to_route('tasks.create', 'タスクの作成', [],
         ['class' => 'btn btn-primary']) !!}
        @if (count($tasks) > 0)
       <table class="table table-striped">
           <thead>
                <tr>
                    <th>id</th>
                    <th>ステータス</th>
                    <th>タスク</th>
                </tr>
           </thead>
           <tbody>
               @foreach ($tasks as $task)
               <tr>
                   <td>{!! link_to_route('tasks.show',$task->id,['task' => $task->id]) !!}</td>
                   <td>{{ $task->status }}</td>
                   <td>{{ $task->content }}</td>
               </tr>
               @endforeach
           </tbody>
        </table>   
        
        @endif
    @else
       <div class="center jumbotron">
            <div class="text-center">
                <h1>ようこそTasklistへ！</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', 'ユーザー登録', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection
