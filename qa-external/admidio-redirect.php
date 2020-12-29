<?php
// admidio-redirect.php
// Admidio redirect patches
// By Abdullah Daud, chelahmy@gmail.com
// 18 December 2020 

// NOTES
// - Admidio does not handle custom redirect after user login, logout or register.
// - Redirection can be achieved by adding a *redirect* parameter to the query string.
// - The followings are small patches to make Admidio to redirect.

// adm_program/system/login.php
// Add the following somewhere at the top of the file

$redirect = admFuncVariableIsValid($_GET, 'redirect', 'string');

if (strlen($redirect) > 0)
    $_SESSION['login_forward_url'] = $redirect;


// adm_program/system/logout.php
// Add the following after $gHomepage assignment

$redirect = admFuncVariableIsValid($_GET, 'redirect', 'string');

if (strlen($redirect) > 0)
    $gHomepage = $redirect; 


// NOTE
// Registration redirection will not work if new registration has to be confirmed.

// adm_program/modules/registration/registration.php
// Add the following somewhere at the top of the file
$redirect = admFuncVariableIsValid($_GET, 'redirect', 'string');

if (strlen($redirect) > 0)
    $_SESSION['registration_redirect'] = $redirect;


// adm_program/modules/profile/profile_save.php
// Add the following at the top of the ($getNewUser === 2) block

if(isset($_SESSION['registration_redirect'])) {
    $redirect = $_SESSION['registration_redirect'];
    unset($_SESSION['registration_redirect']);
    
    if (strlen($redirect) > 0)
        $gHomepage = $redirect;
}

?>

