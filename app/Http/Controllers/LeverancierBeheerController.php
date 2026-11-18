<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLeverancierRequest;
use App\Models\LeverancierBeheer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeverancierBeheerController extends Controller
{
    public function __construct(private LeverancierBeheer $leveranciers) {}

    public function index(Request $request): View
    {
        return view('leverancier.beheer.overzicht', [
            'leveranciers' => $this->leveranciers->pagina($request->integer('page', 1)),
        ]);
    }

    public function show(int $leverancierId): View
    {
        return $this->formulierView('leverancier.beheer.details', $leverancierId);
    }

    public function edit(int $leverancierId): View
    {
        return $this->formulierView('leverancier.beheer.wijzigen', $leverancierId);
    }

    public function update(UpdateLeverancierRequest $request, int $leverancierId): RedirectResponse
    {
        $leverancier = $this->leveranciers->details($leverancierId);
        abort_if($leverancier === null, 404);

        $gegevens = $request->validated();
        $simuleerStoring = app()->environment('testing')
            && config('app.simuleer_leveranciersstoring')
            && $leverancier->Naam === 'De Bron'
            && $gegevens['Mobiel'] === '06-39398825';

        try {
            $this->leveranciers->wijzig($leverancierId, $gegevens, $simuleerStoring);
            $melding = 'De wijzigingen zijn doorgevoerd';
            $gelukt = true;
        } catch (QueryException $exception) {
            report($exception);
            $melding = 'Door een technische storing is het niet mogelijk de wijziging door te voeren. Probeer het op een later moment nog eens';
            $gelukt = false;
        }

        return to_route('leverancier.beheer.edit', [
            'leverancierId' => $leverancierId,
            'page' => $request->integer('page', 1),
        ])
            ->with('leverancierResultaat', compact('melding', 'gelukt'));
    }

    private function formulierView(string $view, int $leverancierId): View
    {
        $leverancier = $this->leveranciers->details($leverancierId);
        abort_if($leverancier === null, 404);

        return view($view, ['leverancier' => $leverancier, 'velden' => UpdateLeverancierRequest::VELDEN]);
    }
}
