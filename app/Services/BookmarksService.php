<?php

namespace App\Services;

use App\Repositories\BookmarksRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BookmarksService
 * @package App\Services
 */
class BookmarksService
{
    protected const DEFAULT_PAGE  = 1;
    protected const DEFAULT_LIMIT = 5;
    protected const DEFAULT_SORT_FIELD = 'created_at';
    protected const DEFAULT_SORT_DIR = 'desc';

    /**
     * @var BookmarksRepository
     */
    private $bookmarksRepository;

    public function __construct(Container $container)
    {
        $this->bookmarksRepository = $container->make(BookmarksRepository::class);
    }

    /**
     * @param array $params
     * @param string $url
     * @return LengthAwarePaginator
     */
    public function getBookmarks(array $params, string $url): LengthAwarePaginator
    {
        $page = $params['page'] ?? self::DEFAULT_PAGE;
        $limit = $params['limit'] ?? self::DEFAULT_LIMIT;
        $sortField = $params['sortField'] ?? self::DEFAULT_SORT_FIELD;
        $sortDir = $params['sortDir'] ?? self::DEFAULT_SORT_DIR;

        [$bookmarks, $total] = $this->bookmarksRepository->getBookmarks($page, $limit, $sortField, $sortDir);
        return new LengthAwarePaginator(
            $bookmarks,
            $total,
            $limit,
            $page,
            ['path' => $url,]
        );
    }

    /**
     * @param string $url
     * @return bool
     */
    public function hasPageUrl(string $url): bool
    {
        return $this->bookmarksRepository->getBookmarksByPageUrl($url);
    }

    /**
     * @param string $url
     * @param string $password
     * @return array
     */
    public function getPageData(string $url, string $password): array
    {
        $contents = @file_get_contents($url);

        if (isset($contents) && is_string($contents)) {
            preg_match('/<title>([^>]*)<\/title>/si', $contents, $match);

            if (isset($match) && is_array($match) && count($match) > 0) {
                $title = strip_tags($match[1]);
            }

            $regex_pattern = "/rel=\"shortcut icon\" (?:href=[\'\"]([^\'\"]+)[\'\"])?/";
            preg_match_all($regex_pattern, $contents, $matches);
            if (isset($matches[1][0])) {
                $favicon = $matches[1][0];

                $favicon_elems = parse_url($favicon);

                if (!isset($favicon_elems['host'])) {
                    $favicon = $url . '/' . $favicon;
                }
            }

        }

        $metaTags = get_meta_tags($url);

        return [
            'favicon' => $favicon ?? null,
            'page_url' => $url,
            'page_title' => $title ?? null,
            'meta_description' => $metaTags['description'] ?? null,
            'meta_keywords' => $metaTags['keywords'] ?? null,
            'password' => md5($password),
            'created_at' => now(),
        ];
    }

    /**
     * @param array $data
     * @return int
     */
    public function bookmarkCreate(array $data): int
    {
        return $this->bookmarksRepository->bookmarkCreate($data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getBookmarkById(int $id)
    {
        return $this->bookmarksRepository->getBookmarkById($id);
    }

    /**
     * @param int $id
     * @param string $password
     * @return mixed
     */
    public function getBookmarkByPassword(int $id, string $password)
    {
        return $this->bookmarksRepository->getBookmarkByPassword($id, $password);
    }

    /**
     * @param int $id
     */
    public function deleteBookmark(int $id)
    {
        $this->bookmarksRepository->deleteBookmark($id);
    }
}