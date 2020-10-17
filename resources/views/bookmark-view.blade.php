@extends('layout.default')

@section('content')
    <div class="modal" tabindex="-1" role="dialog" id="delete-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('bookmark.delete') }}"
                          method="post"
                          class="form-horizontal"
                          id="form-delete">
                        @csrf
                        <input type="hidden" name="id" value="{{ $bookmark->id }}">
                        <div class="input-group mt-3 mb-3">
                            <div class="pb-3">
                                <input type="text" name="password" class="form-control" placeholder="Пароль для удаления"
                                       id="password" required="required" value="">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="bookmark-delete" type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('layout.bookmark-delete') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('layout.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container card-content">
        <div class="view-block">
            <div class="mt-1 mb-1">
                <label>{{ trans('layout.created-at') }}: </label>
                {{ $bookmark->created_at }}
            </div>
            <div class="mt-1 mb-1">
                <label>{{ trans('layout.favicon') }}: </label>
                {{ $bookmark->favicon }}
            </div>
            <div class="mt-1 mb-1">
                <label>{{ trans('layout.page-url') }}: </label>
                {{ $bookmark->page_url }}
            </div>
            <div class="mt-1 mb-1">
                <label>{{ trans('layout.page-title') }}: </label>
                {{ $bookmark->page_title }}
            </div>
            <div class="mt-1 mb-1">
                <label>{{ trans('layout.meta-description') }}: </label>
                {{ $bookmark->meta_description }}
            </div>
            <div class="mt-1 mb-1">
                <label>{{ trans('layout.meta-keywords') }}: </label>
                {{ $bookmark->meta_keywords }}
            </div>
            <div id="modal-error"></div>
            <button type="button" class="btn btn-primary btn-delete" data-toggle="modal">
                {{ trans('layout.bookmark-delete') }}
            </button>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function($) {
            $('.btn-delete').click(function() {
                $('#delete-modal').modal('show');
            });
            $('#bookmark-delete').click(function () {
                let form = $('#form-delete'),
                    blockError = $('#modal-error'),
                    url = form.attr('action');
                $.post(
                    url,
                    form.serialize(),
                    function() {
                    })
                    .success(function (data) {
                        blockError.html('');
                        if (data.message) {
                            blockError.append('<div class="alert alert-danger">' + data.message + '</div>');
                        } else {
                            location.href = '/bookmarks';
                        }
                    });
            });
        });
    </script>
@endpush