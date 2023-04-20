<h1>Modification du mot de passe <?= $_SESSION['profil']['login']?></h1>

<form method="POST" action="<?= URL ?>compte/validation_modificationPassword">
    <div class="mb-3">
        <label for="ancienPassword" class="form-label">Ancien Password :</label>
        <input type="text" class="form-control" id="ancienPassword" name="ancienPassword" required>
    </div>
    <div class="mb-3">
        <label for="nouveauPassword" class="form-label">Nouveau PassWord :</label>
        <input type="password" class="form-control" id="nouveauPassword" name="nouveauPassword" required>
    </div>
    <div class="mb-3">
        <label for="confirmationNouveauPassword" class="form-label">Confirmation Nouveau PassWord :</label>
        <input type="password" class="form-control" id="confirmationNouveauPassword" name="confirmationNouveauPassword" required>
    </div>
    <div id="erreur" class="alert alert-danger d-none">
        Les password ne corresponde pas
    </div>
    <button type="submit" class="btn btn-primary"> Connexion</button>
</form>
