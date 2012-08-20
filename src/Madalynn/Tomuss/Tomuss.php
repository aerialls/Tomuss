<?php

/*
 * This file is part of the Tomuss application.
 *
 * (c) Julien Brochet <mewt@madalynn.eu>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Madalynn\Tomuss;

use Goutte\Client;
use Guzzle\Http\Client as GuzzleClient;
use Madalynn\Tomuss\Note;

/**
 * The Tomuss class
 */
class Tomuss
{
    const VERSION = '2.1-DEV';

    /**
     * The user
     *
     * @var Madalynn\Tomuss\User
     */
    protected $user;

    /**
     * The browser
     *
     * @var Goutte\Client
     */
    protected $client;

    /**
     * Constructor
     *
     * @param $user The user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->user->load();

        $this->client = new Client();
        $guzzleClient = new GuzzleClient('', array(
            'curl.CURLOPT_SSL_VERIFYPEER' => false
        ));

        $this->client->setClient($guzzleClient);
    }

    /**
     * Updates the user
     *
     * @param OutputInterface The output
     */
    public function update()
    {
        $notes = $this->retrieveNotes();

        foreach ($notes as $note) {
            if (false === $this->user->contains($note)) {
                foreach ($this->user->getNotifiers() as $notifier) {
                    $notifier->notify($note);
                }

                $this->user->addNote($note);
            }
        }

        $this->user->dump();
    }

    protected function retrieveNotes()
    {
        $crawler = $this->client->request('GET', 'http://tomuss.univ-lyon1.fr/');
        $form = $crawler->selectButton('Connexion')->form();

        $this->client->submit($form, array(
            'username' => $this->user->getUsername(),
            'password' => $this->user->getPassword()
        ));

        // The CAS returns a ticket token that we need to extract
        $content = $this->client->getResponse()->getContent();
        if (0 === preg_match('/window.location.href="https:\/\/tomuss.univ-lyon1.fr\/\?ticket=([A-Za-z0-9-]+)";/', $content, $matches)) {
            throw new \RuntimeException('Unable to find the ticket token from the CAS response.');
        }

        $notes = array();
        $crawler = $this->client->request('GET', 'https://tomuss.univ-lyon1.fr/?ticket='.$matches[1]);

        $crawler->filter('h2.title')->each(function ($node) use (&$notes) {
            $scriptTag = $node->nextSibling;
            if (null === $scriptTag) {
                return;
            }

            // Get the name for the 'h2' tag
            $name = 'Unknow';
            $matches = array();
            if (preg_match('/: ([^ ]+) ([^(]+) \(/', $node->textContent, $matches)) {
                $name = $matches[1].' '.$matches[2];
            }

            $scriptValue = $scriptTag->textContent;
            if (0 === preg_match_all('/C\(([0-9]+([.][0-9]+)?),"([A-Za-z.]+)","([0-9]{14})"\)/', $scriptValue, $matches, PREG_SET_ORDER)) {
                return;
            }

            foreach ($matches as $data) {
                $t = $data[4];
                $note = new Note($t);

                $note->setAuthor($data[3]);
                $note->setNote($data[1]);
                $note->setName($name);
                $note->setDate(new \DateTime(substr($t, 0, 4).'-'.substr($t, 4, 2).'-'.substr($t, 6, 2).' '.substr($t, 8, 2).":".substr($t, 10, 2).":".substr($t, 12)));

                $notes[] = $note;
            }
        });

        return $notes;
    }
}
