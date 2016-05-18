<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'VerteXVaaR TYPO3 Toolkit',
    'description' => 'Some general stuff for TYPO3. Should enhance your development',
    'category' => 'misc',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-7.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'Oliver Eglseder',
    'author_email' => 'php@vxvr.de',
    'author_company' => 'vxvr.de',
    'version' => '1.0.0',
];
