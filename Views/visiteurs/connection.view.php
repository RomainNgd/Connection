<h1>Page de connexion</h1>

<form method="POST" action="validation_login">
    <div class="mb-3">
        <label for="login" class="form-label">Login</label>
        <input type="text" class="form-control" id="login" name="login">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">PassWord</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>

    <button type="submit" class="btn btn-primary"> Connexion</button>
</form>

<?php
echo password_hash("test", PASSWORD_DEFAULT);