@extends('layout.default')

@section('content')
    <div class="container card-content">
        <div class="create-block">
            @if($errors->any())
                <ul class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <form action="{{ route('bookmark.create') }}"
                  method="post"
                  class="form-horizontal"
                  id="form-create-bookmark">
                @csrf
                <div class="input-group mt-3 mb-3">
                    <div class="pb-5">
                        <input type="text" name="page_url" class="form-control" placeholder="Url страницы"
                               id="page-url" required="required" value="">
                    </div>
                    <div class="pt-3 pb-3">
                        <input type="password" name="password" class="form-control" placeholder="Пароль для удаления"
                               id="bookmark-password" required="required" value="">
                    </div>
                    <div class="pt-5">
                        <div class="input-group-append">
                            <button id="bookmark-create" type="submit" class="btn btn-primary">
                                {{ trans('layout.bookmark-create') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
