<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\TournamentStatus;
use App\Repositories\TournamentRepositoryInterface;
use App\Http\Requests\TournamentRequest;
use App\Models\Sport;

class TournamentController extends Controller
{
    protected $tournamentRepository;

    public function __construct(TournamentRepositoryInterface $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    public function index()
    {
        $tournaments = $this->tournamentRepository->getAllActive();
        return view('tournament_management.index', compact('tournaments'));
    }

    public function create()
    {
        $statuses = TournamentStatus::cases();
        $sports = Sport::where('status', 'active')->get();
        return view('tournament_management.create', compact('statuses', 'sports'));
    }

    public function store(TournamentRequest $request)
    {
        $this->tournamentRepository->create($request->validated());
        return redirect()->route('tournament-management.index')->with('success', 'Tournament created successfully.');
    }

    public function edit($id)
    {
        $tournament = $this->tournamentRepository->find($id);
        $statuses = TournamentStatus::cases();
        $sports = Sport::where('status', 'active')->get();
        return view('tournament_management.edit', compact('tournament', 'statuses', 'sports'));
    }

    public function update(TournamentRequest $request, $id)
    {
        $this->tournamentRepository->update($id, $request->validated());
        return redirect()->route('tournament-management.index')->with('success', 'Tournament updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->tournamentRepository->delete($id);
            return redirect()->route('tournament-management.index')->with('success', 'Tournament deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tournament-management.index')->with('error', 'Failed to delete tournament: ' . $e->getMessage());
        }
    }
}
