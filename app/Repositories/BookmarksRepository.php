<?php

namespace App\Repositories;

use App\Models\Bookmark;

class BookmarksRepository
{
    /**
     * @param int $page
     * @param int $limit
     * @param string $sortField
     * @param string $sortDir
     * @return array
     */
    public function getBookmarks(int $page, int $limit, string $sortField, string $sortDir)
    {
        $query = Bookmark::select([
            'id',
            'favicon',
            'created_at',
            'page_url',
            'page_title',
            'meta_description',
            'meta_keywords',
        ]);
        $total = $query->count();

        $query->offset(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy($sortField, $sortDir);

        return [$query->get()->all(), $total];
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function getBookmarksByPageUrl(string $url)
    {
        return Bookmark::query()->where('page_url', 'ilike', '%' . $url)->exists();
    }

    /**
     * @param array $data
     * @return int
     */
    public function bookmarkCreate(array $data): int
    {
        return Bookmark::insertGetId($data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getBookmarkById(int $id)
    {
        return Bookmark::where('id', $id)->first();
    }

    /**
     * @param int $id
     * @param string $password
     * @return mixed
     */
    public function getBookmarkByPassword(int $id, string $password)
    {
        return Bookmark::select('id')->where('id', $id)->where('password', md5($password))->first();
    }

    /**
     * @param int $id
     */
    public function deleteBookmark(int $id)
    {
        Bookmark::where('id', $id)->delete();
    }
}