<!DOCTYPE html>
<html>

<head>
  <title>Page panier</title>

  <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/css/panier.css', 'resources/js/app.js', 'resources/js/panier.js'])
  </head>

<body>
  <h1>Mon Panier</h1>

  @if($paiements->isEmpty())
    <p>Aucun trajet choisis.</p>
  @else
    <div class="container">
      <div id="trajets-reserves">
        <h2>Vos trajets réservés</h2>
        @foreach($paiements as $paiement)
          <ul class="list-group">
            <li class="list-group-item"><strong>Votre conducteur :</strong> {{ $paiement->NomConducteur }}</li>
            <li class="list-group-item"><strong>Distance :</strong> {{ $paiement->Distance }} km</li>
            <li class="list-group-item"><strong>Nombre de places choisie :</strong> {{ $paiement->NombrePlaces }}</li>
            <li class="list-group-item"><strong>Prix par personne :</strong> {{ $paiement->Prix }} $</li>
            <li class="list-group-item" id="trajet-item">
              <strong>{{ $paiement->Depart }}</strong>
              <img src="{{ asset('images/flecheDest.png') }}" alt="Fleche" class="fleche-image"
                style="vertical-align: middle; margin: 0 5px;">
              <strong>{{ $paiement->Destination }}</strong>
            </li>
            <li class="list-group-item"><strong>Trajet :</strong>
              {{ \Carbon\Carbon::parse($paiement->DateTrajet)->format('d/m/Y') }}
              à {{ \Carbon\Carbon::parse($paiement->HeureTrajet)->format('H:i') }}
            </li>


          </ul>
        @endforeach
      </div>
      <div class="paiement">
        <h3>Récapitulatif des trajets</h3>
        <ul>
          @foreach($paiements as $paiement)
            <li id="trajet-recap">
              <div>{{$paiement->Distance }} km</div>
              <div class="list-group-item" id="trajet-item">
                <strong>{{ $paiement->Depart }}</strong>
                <img src="{{ asset('images/flecheDest.png') }}" alt="Fleche" class="fleche-Tiny"
                  style="vertical-align: middle; margin: 0 5px;">
                <strong>{{ $paiement->Destination }}</strong> <strong> - </strong>
                {{ $paiement->Prix * $paiement->NombrePlaces }} $

              </div>
            </li>
          @endforeach
          <hr>
          <span></span>
          <span id="price" data-paiements='@json($paiements)'>Total :</span>

          <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#paiementModal">Voir le récapitulatif</button>

          <h3 class="mt-3">Payer par conducteur</h3>
          @foreach($paiements->groupBy('IdConducteur') as $conducteurId => $trajetsConducteur)
            <form action="{{ route('payer.panier', [$conducteurId, session('utilisateur_id', 1)]) }}" method="POST" class="mb-2">
              @csrf
              <button type="submit" class="btn btn-success">
                Payer les trajets de {{ $trajetsConducteur->first()->ConducteurPrenom ?? '' }} {{ $trajetsConducteur->first()->ConducteurNom ?? '' }}
              </button>
            </form>
          @endforeach
      </div>
    </div>


    <!-- Bouton pour ouvrir le modal -->

    <!-- Modal paiement -->
    <div class="modal fade" id="paiementModal" tabindex="-1" aria-labelledby="paiementModalLabel" aria-hidden="true"
      data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <span id="ValidationPrice" data-paiements='@json($paiements)'>Total : </span>
            </div>

            <div class="text-center">
              <button type="button" class="btn btn-secondary mb-3" data-bs-dismiss="modal">
                Fermer
              </button>
            </div>

          </div>
        </div>
      </div>
    </div>

  @endif
  <button class="btn btn-success btn-home" onclick="location.href='/'">Retourner à l'accueil</button>
</body>

</html>