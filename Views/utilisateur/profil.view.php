
<h3>Profil de l'utilisateur <?= $utilisateur['login'] ?></h3>
<div id="mail">
        mail : <?= $utilisateur['mail'] ?>
 </div>
 <div id="active">
     active : <?php if ($utilisateur['is_valid']):?> activé <? else:?> non actif <?php endif;?>
 </div>
 <div id="role">
     mail : <?= $utilisateur['role'] ?>
 </div>
<div id="image">
    mail : <?= $utilisateur['image'] ?>
</div>
<button type="submit" id="btnModifMail" class="btn btn-primary"> Modifié l'adresse mail</button>

<div id="modification-email" class="d-none">
    <form method="POST" action="<?= URL . 'compte/validation_modificationMail' ?>">
        <div class="mb-3">
            <label for="mail" class="form-label">Nouveau mail :</label>
            <input type="text" class="form-control" id="mail" name="mail" value="<?= $utilisateur['mail'] ?>">
        </div>

        <button type="submit" class="btn btn-success" id="btnValidModifMail"> Valider</button>
    </form>
</div>