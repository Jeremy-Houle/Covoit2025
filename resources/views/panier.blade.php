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
                    <img src="{{ asset('images/flecheDest.png') }}" alt="Fleche" class="fleche-image" style="vertical-align: middle; margin: 0 5px;">
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
                    <img src="{{ asset('images/flecheDest.png') }}" alt="Fleche" class="fleche-Tiny" style="vertical-align: middle; margin: 0 5px;">
                    <strong>{{ $paiement->Destination }}</strong> <strong>  - </strong> 
                      {{ $paiement->Prix * $paiement->NombrePlaces }} $
               
                </div>
                 </li>
          @endforeach
     <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#paiementModal">
            Payer
        </button>
       </div>
       </div>
       
     
        <!-- Bouton pour ouvrir le modal -->
        
         <!-- Modal paiement -->
    <div class="modal fade" id="paiementModal" tabindex="-1" aria-labelledby="paiementModalLabel" aria-hidden="true"  data-bs-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="paiementModalLabel">Formulaire de paiement</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <form method="POST">
              @csrf
              <div class="mb-3">
                <label for="nomCarte" class="form-label">Nom sur la carte</label>
                <input type="text" class="form-control" id="nomCarte" name="nomCarte" required>
              </div>
              <div class="mb-3">
                <label for="numCarte" class="form-label">Numéro de carte</label>
                <input type="text" class="form-control" id="numCarte" name="numCarte" maxlength="19" inputmode="numeric" required>
              </div>
              <div class="mb-3">
                <label for="dateExp" class="form-label">Date d'expiration</label>
                <input type="text" id="dateExp" class="form-control" placeholder="MM/YY" maxlength="5" required>
              </div>
              <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" maxlength="3" inputmode="numeric" required>
              </div>
              <button type="submit" class="btn btn-success">Valider le paiement</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    @endif
    <button class="btn btn-success btn-home" onclick="location.href='/'">Retourner à l'accueil</button>
</body>
</html>
