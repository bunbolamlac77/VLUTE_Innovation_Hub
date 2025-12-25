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
        if (!\in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        // Fetch filters data
        $faculties = Faculty::orderBy('name')->get(['id','name']);
        $categories = Category::orderBy('name')->get(['id','name']);

        // Prepare results
        $ideas = null; /** @var LengthAwarePaginator|null $ideas */
        $competitions = null; /** @var LengthAwarePaginator|null $competitions */
        $mentors = null; /** @var LengthAwarePaginator|null $mentors */

        // Helper: improved search with relevance scoring and better matching
        $applyLike = function ($query) use ($q) {
            if ($q === '') return $query;
            
            // Escape special LIKE characters
            $escapedQ = str_replace(['%', '_'], ['\%', '\_'], $q);
            
            // Split query into individual words
            $words = array_filter(
                preg_split('/\s+/', trim($q)),
                function($word) {
                    return mb_strlen(trim($word)) > 0;
                }
            );
            
            return $query->where(function ($s) use ($escapedQ, $words) {
                // Exact phrase match (highest priority)
                $s->where(function ($exact) use ($escapedQ) {
                    $exact->where('title', 'like', "%{$escapedQ}%")
                          ->orWhere('summary', 'like', "%{$escapedQ}%")
                          ->orWhere('description', 'like', "%{$escapedQ}%");
                });
                
                // If multiple words, also match all words (AND logic)
                if (count($words) > 1) {
                    $s->orWhere(function ($allWords) use ($words) {
                        foreach ($words as $word) {
                            $escapedWord = str_replace(['%', '_'], ['\%', '\_'], trim($word));
                            $allWords->where(function ($w) use ($escapedWord) {
                                $w->where('title', 'like', "%{$escapedWord}%")
                                  ->orWhere('summary', 'like', "%{$escapedWord}%")
                                  ->orWhere('description', 'like', "%{$escapedWord}%");
                            });
                        }
                    });
                }
            })->orderByRaw("
                CASE 
                    WHEN title LIKE ? THEN 1
                    WHEN summary LIKE ? THEN 2
                    WHEN description LIKE ? THEN 3
                    ELSE 4
                END ASC
            ", ["%{$escapedQ}%", "%{$escapedQ}%", "%{$escapedQ}%"]);
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

            // Order by relevance first (if search query exists), then by date
            if ($q !== '') {
                // Relevance ordering is already applied in applyLike via orderByRaw
                // Add secondary ordering by date
                $ideas = $ideasQuery->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            } else {
                $ideas = $ideasQuery->latest()->paginate(10)->withQueryString();
            }
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

