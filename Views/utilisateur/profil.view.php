
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