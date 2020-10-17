<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookmarkCreateRequest;
use App\Http\Requests\BookmarksRequest;
use App\Http\Requests\PasswordRequest;
use App\Services\BookmarksService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Class BookmarkController
 * @package App\Http\Controllers
 */
class BookmarkController extends Controller
{
    protected $bookmarksService;

    public function __construct(Request $request, Container $container)
    {
        $this->bookmarksService = $container->make(BookmarksService::class);
        parent::__construct($request, $container);
    }

    /**
     * @param BookmarksRequest $request
     * @return View
     */
    public function index(BookmarksRequest $request): View
    {
        $bookmarks = $this->bookmarksService->getBookmarks($request->all(), $request->url());
        return view('bookmarks', ['bookmarks' => $bookmarks]);
    }

    /**
     * @return View
     */
    public function bookmarkNew(): View
    {
        return view('bookmark-create');
    }

    /**
     * @param BookmarkCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(BookmarkCreateRequest $request)
    {
        $pageUrl = preg_replace('/^(https?:)?(\/\/)?(www\.)?/', '', $request->get('page_url'));

        if (!$this->bookmarksService->hasPageUrl($pageUrl)) {
            $bookmark = $this->bookmarksService->getPageData($request->get('page_url'), $request->get('password'));
            $bookmarkId = $this->bookmarksService->bookmarkCreate($bookmark);
        } else {
            return redirect()->route('bookmark.new')->withErrors([trans('errors.has-page')]);
        }

        return redirect()->to('/bookmark/view/' . $bookmarkId);
    }

    /**
     * @param int $id
     * @return View
     */
    public function bookmarkView(int $id): View
    {
        $bookmark = $this->bookmarksService->getBookmarkById($id);
        return view('bookmark-view', ['bookmark' => $bookmark]);
    }

    /**
     * @param PasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bookmarkDelete(PasswordRequest $request)
    {
        $id = $request->get('id');
        $password = $request->get('password');
        $bookmark = $this->bookmarksService->getBookmarkByPassword($id, $password);
        if (empty($bookmark)) {
            return response()->json(['message' => trans('errors.password')]);
        }

        $this->bookmarksService->deleteBookmark($id);
        return redirect()->to('/bookmarks');
    }
}
