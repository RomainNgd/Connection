
<h3>Profil de l'utilisateur <?php $utilisateur['login'] ?></h3>
<div id="mail">
        mail : <?php $utilisateur['mail'] ?>
 </div>
 <div id="active">
     active : <?php if ($utilisateur['is_active']):?> activé <? else:?> non actif <?php endif;?>
 </div>
 <div id="role">
     mail : <?php $utilisateur['role'] ?>
 </div>
<div id="image">
    mail : <?php $utilisateur['image'] ?>
</div>