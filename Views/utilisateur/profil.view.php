
<h3>Profil de l'utilisateur <?= $utilisateur['login'] ?></h3>
<div id="role">
    role : <?= $utilisateur['role'] ?>
</div>
<div id="mail">
        <p>mail : <?= $utilisateur['mail'] ?></p>
    <button type="submit" id="btnModifMail" class="btn btn-primary"> Modifié l'adresse mail</button>
 </div>


<div id="modificationMail" class="d-none">
    <form method="POST" action="<?= URL . 'compte/validation_modificationMail' ?>">
        <div class="mb-3">
            <label for="mail" class="form-label">Nouveau mail :</label>
            <input type="text" class="form-control" id="mail" name="mail" value="<?= $utilisateur['mail'] ?>">
        </div>

        <button type="submit" class="btn btn-success" id="btnValidModifMail"> Valider</button>
    </form>
</div>

<div>
    <a href="<?= URL ?>compte/modificationPassword" class="btn btn-warning">Changer le mot de passe</a>
    <button id="btnSupCompte" class="btn btn-dnager">Supprimer son compte</button>
</div>
<div id="suppressionCompte" class="d-none m-2">
    <div class="alert alert-danger">
        Veuillez confirmer la suppresion du compte
        <br/>
        <a href="<?= URL ?>compte/suppresionCompte" class="btn btn-danger">Je souhaite supprimmer mon compte !</a>
    </div>
</div>