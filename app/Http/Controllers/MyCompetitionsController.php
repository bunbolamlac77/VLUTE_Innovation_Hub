<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MyCompetitionsController extends Controller
{
    /**
     * Hiển thị danh sách các cuộc thi mà user đã đăng ký.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status'); // open|closed|null
        $sort = $request->get('sort', 'recent'); // recent|deadline_asc|deadline_desc

        $query = Auth::user()->competitionRegistrations()
            ->with('competition')
            ->withCount('submissions');

        if ($status === 'open') {
            $query->whereHas('competition', function ($q) {
                $q->where('status', 'open')
                  ->where(function ($q2) {
                      $q2->whereNull('end_date')->orWhere('end_date', '>', now());
                  });
            });
        } elseif ($status === 'closed') {
            $query->whereHas('competition', function ($q) {
                $q->where('status', '!=', 'open')->orWhere(function ($q2) {
                    $q2->where('status', 'open')->whereNotNull('end_date')->where('end_date', '<=', now());
                });
            });
        }

        if (\in_array($sort, ['deadline_asc', 'deadline_desc'], true)) {
            $direction = $sort === 'deadline_asc' ? 'asc' : 'desc';
            $query->leftJoin('competitions', 'competition_registrations.competition_id', '=', 'competitions.id')
                ->orderBy('competitions.end_date', $direction)
                ->select('competition_registrations.*');
        } else {
            $query->latest();
        }

        $registrations = $query->paginate(15)->withQueryString();

        return view('my-competitions.index', [
            'registrations' => $registrations,
            'filters' => [
                'status' => $status,
                'sort' => $sort,
            ],
        ]);
    }
}
