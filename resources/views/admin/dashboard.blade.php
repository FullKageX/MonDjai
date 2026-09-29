<h1>Dashboard Admin</h1>

<h2>Tontines en attente d'activation</h2>

@forelse ($tontinesEnAttente as $tontine)
    <div>
        <strong>{{ $tontine->nom }}</strong> — {{ $tontine->montant_cotisation }} FCFA / {{ $tontine->frequence }}

        <form method="POST" action="{{ route('admin.tontines.activer', $tontine) }}">
            @csrf
            <button type="submit">Activer</button>
        </form>
    </div>
@empty
    <p>Aucune tontine en attente.</p>
@endforelse