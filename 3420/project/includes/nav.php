        <nav>
            <ul>
                <li class="<?=!isset($_SESSION['id']) ? 'hidden' : "";?>"><a href="Project_add_wishlist.php" title="">Create a Wishlist</a></li>
                <li class="<?=!isset($_SESSION['id']) ? 'hidden' : "";?>"><a href="index.php" title="">View All Wishlists</a></li>
                <li class="<?=isset($_SESSION['id']) ? 'hidden' : "";?>"><a href="Project_login.php" title="Login">Login</a></li>
                <li class="<?=isset($_SESSION['id']) ? 'hidden' : "";?>"><a href="Project_createAccount.php" title="Create an Account">Create an Account</a></li>
                <li class="<?=isset($_SESSION['id']) ? 'hidden' : "";?>"><a href="Project_forgot_password.php" title="Forgot Password">Forgot Password</a></li>
                <li class="<?=!isset($_SESSION['id']) ? 'hidden' : "";?>"><a href="Project_account_settings.php" title="Account Settings">Account Settings</a><li>
                <li class="<?=!isset($_SESSION['id']) ? 'hidden' : "";?>"><a href="Project_login.php?logout=true" title="Logout">Logout</a></li>
            </ul>
        </nav>
