<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Faculty;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $type = $request->input('type', 'all'); // all|ideas|competitions|mentors
        $facultyId = $request->input('faculty');
        $categoryId = $request->input('category');

        $allowedTypes = ['all', 'ideas', 'competitions', 'mentors'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        // Fetch filters data
        $faculties = Faculty::orderBy('name')->get(['id','name']);
        $categories = Category::orderBy('name')->get(['id','name']);

        // Prepare results
        $ideas = null; /** @var LengthAwarePaginator|null $ideas */
        $competitions = null; /** @var LengthAwarePaginator|null $competitions */
        $mentors = null; /** @var LengthAwarePaginator|null $mentors */

        // Helper: wrap like query safely
        $applyLike = function ($query) use ($q) {
            if ($q === '') return $query;
            return $query->where(function ($s) use ($q) {
                $s->where('title', 'like', "%{$q}%")
                  ->orWhere('summary', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        };

        if ($type === 'all' || $type === 'ideas') {
            $ideasQuery = Idea::query()
                ->publicApproved()
                ->with(['faculty','category','owner']);

            $ideasQuery = $applyLike($ideasQuery);

            if ($facultyId) {
                $ideasQuery->where('faculty_id', $facultyId);
            }
            if ($categoryId) {
                $ideasQuery->where('category_id', $categoryId);
            }

            $ideas = $ideasQuery->latest()->paginate(10)->withQueryString();
        }

        if ($type === 'all' || $type === 'competitions') {
            $competitionsQuery = Competition::query();
            if ($q !== '') {
                $competitionsQuery->where(function ($s) use ($q) {
                    $s->where('title', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                });
            }
            $competitions = $competitionsQuery->latest()->paginate(10)->withQueryString();
        }

        if ($type === 'all' || $type === 'mentors') {
            $mentorsQuery = User::query()
                ->with(['profile'])
                ->where(function ($s) {
                    $s->where('role', 'staff');
                });

            if ($q !== '') {
                $mentorsQuery->where(function ($s) use ($q) {
                    $s->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhereHas('profile', function ($p) use ($q) {
                          $p->where('department', 'like', "%{$q}%")
                            ->orWhere('interest_field', 'like', "%{$q}%");
                      });
                });
            }

            // Optional filter by faculty when available via profile
            if ($facultyId) {
                $mentorsQuery->whereHas('profile', function ($p) use ($facultyId) {
                    $p->where('faculty_id', $facultyId);
                });
            }

            $mentors = $mentorsQuery->latest('id')->paginate(10)->withQueryString();
        }

        return view('search.index', compact('q', 'type', 'faculties', 'categories', 'ideas', 'competitions', 'mentors', 'facultyId', 'categoryId'));
    }
}

