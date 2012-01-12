<?php

require_once __DIR__.'/vendor/.composer/autoload.php';

if (3 !== count($argv)) {
    echo "Too few arguments\n";
    echo "Syntax: php get.php <username> <password>\n";
    exit;
}

$notesFile = __DIR__.'/notes';
$notes = array();

if (true === file_exists($notesFile)) {
    $notes = unserialize(file_get_contents($notesFile));
}

$browser = new Buzz\Browser(new Buzz\Client\Curl());

// ####################################
//    CAS Connection
// ####################################

$response = $browser->get('http://tomuss.univ-lyon1.fr');

// "lt" hidden field
if (0 === preg_match('/name="lt" value="([A-Za-z0-9-]+)"/', $response->getContent(), $matches)) {
    echo "Oops! Unable to find the 'lt' hidden field from the content reponse... Are you in the CAS login form?\n";
    exit;
}

// Fields
$lt = $matches[1];
$username = $argv[1];
$password = $argv[2];

$response = $browser->submit('https://cas.univ-lyon1.fr/cas/login?service=https://tomuss.univ-lyon1.fr/', array(
    'username' => $username,
    'password' => $password,
    'lt'       => $lt
));

if (preg_match('/un nom d\'utilisateur ou un mot de passe invalide/', $response->getContent())) {
    echo "Bad login/password.\n";
    exit;
}

// Stupid javascript redirection ... We NEED the token!
if (0 === preg_match('/window.location.href="https:\/\/tomuss.univ-lyon1.fr\/\?ticket=([A-Za-z0-9-]+)";/', $response->getContent(), $matches)) {
    echo "Oops! Unable to find the token from the CAS...\n";
    exit;
}
