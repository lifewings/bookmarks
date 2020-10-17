@extends('layout.default')

@section('content')
    <div class="container card-content ml-2">
        <div>
            <div class="float-right mt-3 mb-2">
                <a class="btn btn-info" role="button" id="bookmark-new" href="{{ route('bookmark.new') }}">
                    <span>
                        {{ trans('layout.bookmark-create') }}
                    </span>
                </a>
                <a class="btn btn-info" role="button" id="bookmark-export" href="{{ route('bookmark.export') }}">
                    <span>
                        {{ trans('layout.bookmark-export') }}
                    </span>
                </a>
            </div>
            <table class="table table-responsive" id="bookmarks-table">
                <thead class="thead-light">
                <tr>
                    <th>
                        <div class="row" style="margin: auto;">
                            {{ trans('layout.created-at') }}
                            <div class="nav-filter">
                                <span class="nav-filter__link nav-filter__link--arrow nav-filter__link--up"
                                      onclick="getWithSorts('created_at', 'asc')"></span>
                                <span class="nav-filter__link nav-filter__link--arrow nav-filter__link--down"
                                      onclick="getWithSorts('created_at', 'desc')"></span>
                            </div>
                        </div>
                    </th>
                    <th>{{ trans('layout.favicon') }}</th>
                    <th>{{ trans('layout.page-url') }}</th>
                    <th>{{ trans('layout.page-title') }}</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody class="pagination-content">
                @forelse($bookmarks->items() as $item)
                    <tr>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->favicon }}</td>
                        <td>{{ $item->page_url }}</td>
                        <td>{{ $item->page_title }}</td>
                        <td>
                            <a class="btn btn-info" role="button" href="{{ route('bookmark.view', ['id' => $item->id]) }}">
                                {{ trans('layout.view') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            {{ trans('layout.reviews-not-found') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="row">
                <div class="col-12">
                    {{ $bookmarks->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let getWithSorts = (function (sortField, sortDir) {
            $.get(
                window.location.href,
                {
                    sortField: sortField,
                    sortDir: sortDir
                },
                function(data) {
                    $('body').html(data);
                }
            );
        });
    </script>
    <style>
        .nav-filter {
            align-self: center;
        }

        .nav-filter__link {
            position: relative;
            margin-right: 24px;
            padding-left: 20px;
            cursor: pointer
        }

        .nav-filter__link--arrow:before {
             position: absolute;
             content: '';
             bottom: 0;
             left: 0;
             border: solid #266d97;
             border-width: 0 2px 2px 0;
             display: inline-block;
             padding: 4px;
        }

        .nav-filter__link--up:before {
            transform: rotate(-135deg);
        }

        .nav-filter__link--down:before {
             transform: rotate(45deg);
             bottom: 50%;
        }
    </style>
@endpush
