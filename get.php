<?php

/*
 * (c) 2012 Julien Brochet <mewt@madalynn.eu>
 */

require_once __DIR__.'/vendor/.composer/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

$length = count($argv);
if ($length < 3 || $length > 4) {
    echo "Too few arguments\n";
    echo "Syntax: php get.php <username> <password> [get|show]\n";
    exit;
}

// Check for action
$action = 'get';
if (isset($argv[3])) {
    if (false === in_array($argv[3], array('get', 'show'))) {
        throw new InvalidArgumentException('The action must be (get,show).');
    }

    $action = $argv[3]; // 'get' or 'show'
}

function println($message, $title = 'Tomuss')
{
    echo $title.': '.$message.PHP_EOL;

    // Growl notification
    $grownotifier = '/usr/local/bin/growlnotify';
    exec(sprintf('[ -e %s ] && %s -t "%s" -n "Tomuss" -m "%s"',
            $grownotifier,
            $grownotifier,
            $title,
            $message
    ));
}

function displayNote($note)
{
    println(sprintf('%s : %s en %s -- %s', $note['date']->format('d/m/Y à H:i'), $note['note'], $note['name'], str_replace('.', ' ', strtoupper($note['by']))));
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
$matches = array();
if (0 === preg_match('/name="lt" value="([A-Za-z0-9-]+)"/', $response->getContent(), $matches)) {
    println('Oops! Unable to find the "lt" hidden field from the content reponse... Are you in the CAS login form?');
    exit;
}

// Fields
$lt       = $matches[1];
$username = $argv[1];
$password = $argv[2];

$response = $browser->submit('https://cas.univ-lyon1.fr/cas/login?service=https://tomuss.univ-lyon1.fr/', array(
    'username' => $username,
    'password' => $password,
    'lt'       => $lt
));

if (preg_match('/un nom d\'utilisateur ou un mot de passe invalide/', $response->getContent())) {
    println('Bad login/password');
    exit;
}

// Stupid javascript redirection ... We NEED the token!
if (0 === preg_match('/window.location.href="https:\/\/tomuss.univ-lyon1.fr\/\?ticket=([A-Za-z0-9-]+)";/', $response->getContent(), $matches)) {
    println('Oops! Unable to find the token from the CAS...');
    exit;
}

$token = $matches[1];

// ####################################
//    Tomuss
// ####################################

// Big hack: The curl PHP API returns 0 bytes with this URL
$output = exec('curl -k -L https://tomuss.univ-lyon1.fr/?ticket='.$token.' 2> /dev/null');
$regex = '/C\(([0-9]+([.][0-9]+)?),"([A-Za-z.]+)","([0-9]{14})"\)/';
$new = false;

// Symfony2 Crawler
$crawler = new Crawler();
$crawler->addContent($output);

$crawler->filter('h2.title')->each(function ($node, $i) use ($action, &$new, &$notes, $regex) {
    $scriptTag = $node->nextSibling;
    if (null === $scriptTag) {
        return;
    }

    // Get the name for the 'h2' tag
    $matches = array();
    $name = 'Unknow';
    if (preg_match('/: ([^ ]+) ([^(]+) \(/', $node->textContent, $matches)) {
        $name = $matches[1].' - '.$matches[2];
    }

    $scriptValue = $scriptTag->textContent;
    if (0 === preg_match_all($regex, $scriptValue, $matches, PREG_SET_ORDER)) {
        return;
    }

    foreach($matches as $note) {
        if (isset($notes[$note[4]])) {
            // Note alreay exists
            continue;
        }

        $new = true;
        $d = $note[4];

        $notes[$d] = array(
            'by'   => $note[3],
            'note' => $note[1],
            'name' => $name,
            'date' => new DateTime(substr($d, 0, 4).'-'.substr($d, 4, 2).'-'.substr($d, 6, 2).' '.substr($d, 8, 2).":".substr($d, 10, 2).":".substr($d, 12))
        );

        // Display the note
        if ('get' === $action) {
            displayNote($notes[$note[4]]);
        }
    }
});

if (true === $new) {
    file_put_contents($notesFile, serialize($notes));
} else if ('get' === $action) {
    println('Aucune nouvelle note disponible.');
}

if ('show' === $action) {
    foreach($notes as $note) {
        displayNote($note);
    }
}