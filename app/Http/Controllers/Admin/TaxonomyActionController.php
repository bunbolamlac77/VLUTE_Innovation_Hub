<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxonomyActionController extends Controller
{
    // FACULTY
    public function storeFaculty(Request $r)
    {
        \App\Models\Faculty::create($r->validate(['name' => 'required', 'code' => 'nullable']));
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã thêm khoa');
    }
    public function updateFaculty(Request $r, \App\Models\Faculty $faculty)
    {
        $faculty->update($r->validate(['name' => 'required', 'code' => 'nullable']));
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã lưu khoa');
    }
    public function destroyFaculty(\App\Models\Faculty $faculty)
    {
        $faculty->delete();
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã xoá khoa');
    }

    // CATEGORY
    public function storeCategory(Request $r)
    {
        \App\Models\Category::create($r->validate(['name' => 'required', 'slug' => 'nullable']));
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã thêm danh mục');
    }
    public function updateCategory(Request $r, \App\Models\Category $category)
    {
        $category->update($r->validate(['name' => 'required', 'slug' => 'nullable']));
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã lưu danh mục');
    }
    public function destroyCategory(\App\Models\Category $category)
    {
        $category->delete();
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã xoá danh mục');
    }

    // TAG
    public function storeTag(Request $r)
    {
        \App\Models\Tag::create($r->validate(['name' => 'required', 'slug' => 'nullable']));
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã thêm tag');
    }
    public function updateTag(Request $r, \App\Models\Tag $tag)
    {
        $tag->update($r->validate(['name' => 'required', 'slug' => 'nullable']));
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã lưu tag');
    }
    public function destroyTag(\App\Models\Tag $tag)
    {
        $tag->delete();
        return to_route('admin.home', ['tab' => 'taxonomies'])->with('status', 'Đã xoá tag');
    }
}