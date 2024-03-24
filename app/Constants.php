<?php


namespace App;

use function PHPSTORM_META\map;

class Constants
{
    const SUPPLIERS = [
        [
            'supplierName' => 'ELO Digital Office GmbH',
            'url' => 'www.elo.com',
            'addressFirst' => 'Tübinger Straße 43',
            'city' => 'Stuttgart',
            'zip' => '70175',
            'state' => 'Deutschland',
            'vatId' => 'DE812471516',
            'phone' => '+49 711 806089 0',
            'bankDetails' => [
                [
                    'bankName' => 'Landesbank Baden-Württemberg/Baden-Württembergische Bank',
                    'swift' => 'SOLADEST600',
                    'iban' => 'DE67600501010002089782'
                ]
            ],
        ],
        [
            'supplierName' => 'easybell GbmH',
            'url' => 'www.easybell.de',
            'addressFirst' => 'Brückenstraße 5A',
            'city' => 'Berlin',
            'zip' => '10179',
            'state' => 'Deutschland',
            'vatId' => 'DE249984363',
            'phone' => '+49 30 8095 1020',
            'bankDetails' => [
                [
                    'bankName' => 'Commerzbank AG',
                    'swift' => 'COBADEFFXXX',
                    'iban' => 'DE35 4804 0035 0760 6890 00'
                ]
            ],
        ],
        [
            'supplierName' => 'Carl Rieck GmbH',
            'url' => 'www.carlrieck.de',
            'addressFirst' => 'Carl-Zeiss-Straße 10/4',
            'city' => 'Rödermark',
            'zip' => '63322',
            'state' => 'Deutschland',
            'vatId' => 'DE232878276',
            'phone' => '+49 6074 69665 0',
            'bankDetails' => [
                [
                    'bankName' => 'Commerzbank Hamburg',
                    'swift' => 'COBADEFFXXX',
                    'iban' => 'DE32200400000621947100'
                ]
            ],
        ],
        [
            'supplierName' => 'Bühler + Ertle Steuerberater',
            'url' => 'www.bestb.de',
            'addressFirst' => 'Erbprinzenstraße 32',
            'city' => 'Rödermark',
            'zip' => '76133',
            'state' => 'Deutschland',
            'vatId' => 'DE143613231',
            'phone' => '+49 721 912 11 0',
            'bankDetails' => [
                [
                    'bankName' => 'Deutsche Bank Kadsruhe',
                    'swift' => 'DEUTDED8660',
                    'iban' => 'DE15 6607 0024 0085 501 5 00'
                ],
                [
                    'bankName' => 'Sparkasse Karlsruhe',
                    'swift' => 'KARSDE66',
                    'iban' => 'DE41 6605 0101 0010 5010 39'
                ]
            ],
        ],
        [
            'supplierName' => 'Hetzner Online GmbH',
            'url' => 'www.hetzner.com',
            'addressFirst' => 'Industriestr. 25',
            'city' => 'Gunzenhausen',
            'zip' => '91710',
            'state' => 'Deutschland',
            'vatId' => 'DE812871812',
            'phone' => '+49 9831 505 0',
            'bankDetails' => [
                [
                    'bankName' => 'Deutsche Bank AG Nürnberg',
                    'swift' => 'DEUTDEMM760',
                    'iban' => 'DE92 7607 0012 0750 0077 00'
                ]
            ],
        ],
        [
            'supplierName' => 'Nufin GmbH',
            'url' => 'www.getmoss.com',
            'addressFirst' => 'Ziegelstraße 16',
            'city' => 'Berlin',
            'zip' => '10117',
            'state' => 'Deutschland',
            'vatId' => 'DE812871812',
            'phone' => '+49 30 311937 30',
        ],
        [
            'supplierName' => 'Code Foresight',
            'url' => 'www.codeforesight.com',
            'addressFirst' => 'Building #116, Block F1, Johar Town',
            'city' => 'Lahore',
            'zip' => 'NOZIPCODE',
            'country' => 'Pakistan',
            'state' => 'Punjab',
            'vatId' => 'NOID',
            'phone' => '+92 316 0417949',
            'bankDetails' => [
                [
                    'bankName' => 'BANK ALFALAH LTD',
                    'swift' => 'ALFHPKKAXXX',
                    'iban' => 'PK06ALFH0210001007955899'
                ]
            ],
        ],
        [
            'supplierName' => 'Enterprise Autovermietung Deutschland B.V. & Co. KG',
            'url' => 'www.enterprise.de',
            'addressFirst' => 'LOCHHAMER SCHLAG 17',
            'city' => 'GRAFELFING',
            'zip' => '82166',
            'state' => 'Deutschland',
            'vatId' => 'DE812290150',
            'phone' => '+49 898996800'
        ],
        [
            'supplierName' => 'Stadt Karlsruhe',
            'url' => 'www.karlsruhe.de',
            'addressFirst' => 'Kaiserallee 8',
            'city' => 'Karlsruhe',
            'zip' => '76133',
            'state' => 'Deutschland',
            'vatId' => 'NOID',
            'phone' => '+49 721 133 3249',
            'bankDetails' => [
                [
                    'bankName' => 'Sparkasse Karlsruhe Ettlingen',
                    'swift' => 'KARSDE66XXX',
                    'iban' => 'DE66660501010009000969'
                ]
            ],
        ],
        [
            'supplierName' => 'Löbbecke, Gövert, Behler & Partner',
            'url' => 'www.loebbecke-goevert.de',
            'addressFirst' => 'Horster Str. 19',
            'city' => 'Gladbeck',
            'zip' => '45964',
            'state' => 'Deutschland',
            'vatId' => 'NOID',
            'phone' => '+49 2043 988 86 10',
            'bankDetails' => [
                [
                    'bankName' => 'Stadtsparkasse Gladbeck',
                    'swift' => 'WELADED1GLA',
                    'iban' => 'DE14424500400000111690'
                ],
                [
                    'bankName' => 'Volksbank Ruhr Mitte eG',
                    'swift' => 'GENODEM1GBU',
                    'iban' => 'DE36422600010004111000'
                ]
            ],
        ],
        [
            'supplierName' => 'B&B Hotel Stuttgart-Bad Cannstatt',
            'url' => 'www.hotel-bb.com',
            'addressFirst' => 'König-Karl-Straße 78',
            'city' => 'Stuttgart',
            'zip' => '70372',
            'state' => 'Deutschland',
            'vatId' => 'DE298095507',
            'phone' => '+49 711 6335799 0',
            'bankDetails' => [
                [
                    'bankName' => 'Postbank Köln',
                    'swift' => 'PBNKDEFF',
                    'iban' => 'DE69370100500981243505'
                ]
            ],
        ]
    ];
    const PRODUCT_CONSTANTS = [
        [
            'name' => 'ELO ECM Suite Professional',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'ELO ECM Suite Professional',
            'discount' => 0,
            'listingPrice' => 895,
            'salePrice' => 895,
            'profit' => 268.50,
            'tax' => 19,
            'executionNumber' => 10,
            'productGroup' => ['id' => 1, 'name' => 'ELO BASIS'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 161.10,
            'manufacturerNumber' => 'PNF-123',
            'paymentDetailsType' => 'software',
            "versions" => [
                [
                    "name" => "1",
                ]
            ],
        ],
        [
            'name' => 'ELO ECM Suite Enterprise',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'ELO ECM Suite Enterprise',
            'discount' => 0,
            'listingPrice' => 1150,
            'salePrice' => 1150,
            'profit' => 345.00,
            'tax' => 19,
            'executionNumber' => 1,
            'productGroup' => ['id' => 1, 'name' => 'ELO BASIS'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 207,
            'manufacturerNumber' => 'PNF-123',
            'paymentDetailsType' => 'software',
            "versions" => [
                [
                    "name" => "1.25",
                ]
            ],
        ],
        [
            'name' => 'BS Invoice 050 Basis',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'BS Invoice 050 Basis 12.500 Seiten / Jahr',

            'discount' => 0,
            'listingPrice' => 2950,
            'salePrice' => 2950,
            'profit' => 885.00,
            'tax' => 19,
            'executionNumber' => 1,
            'productGroup' => ['id' => 2, 'name' => 'ELO Business Solution'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 531.00,
            'paymentDetailsType' => 'software',
            'manufacturerNumber' => 'PNF-123',
            "versions" => [
                [
                    "name" => "1.29",
                ]
            ],
        ],
        [
            'name' => 'BS Invoice 200 Plus',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'BS Invoice 100 Plus weitere 50.000 Seiten / Jahr',

            'discount' => 0,
            'listingPrice' => 4950,
            'salePrice' => 4950,
            'profit' => 1485.00,
            'tax' => 19,
            'executionNumber' => 2,
            'paymentDetailsType' => 'software',
            'productGroup' => ['id' => 2, 'name' => 'ELO Business Solution'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 891.00,
            'manufacturerNumber' => 'PNF-123',
            "versions" => [
                [
                    "name" => "1.2.1",
                ]
            ],
        ],
        [
            'name' => 'BS Contract Entry',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'BS Contract Entry bis 100 User',

            'discount' => 0,
            'listingPrice' => 2490,
            'salePrice' => 2490,
            'profit' => 747.00,
            'tax' => 19,
            'executionNumber' => 0,
            'paymentDetailsType' => 'software',
            'productGroup' => ['id' => 2, 'name' => 'ELO Business Solution'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 448.20,
            'manufacturerNumber' => 'PNF-123',
            "versions" => [
                [
                    "name" => "1.4.1",
                ]
            ],
        ],
        [
            'name' => 'BS Contract Advanced',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'BS Contract Advanced bis 1000 User',

            'discount' => 0,
            'listingPrice' => 4750,
            'salePrice' => 4750,
            'profit' => 1425.00,
            'tax' => 19,
            'executionNumber' => 2,
            'paymentDetailsType' => 'software',
            'productGroup' => ['id' => 2, 'name' => 'ELO Business Solution'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 855.00,
            'manufacturerNumber' => 'PNF-123',
            "versions" => [
                [
                    "name" => "1.4.1",
                ]
            ],
        ],
        [
            'name' => 'ELO Barcode Server',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'Lizenz pro Server: zur Lizenzierung einer zentralen-/serverbasierten Barcode-
Verarbeitung',

            'discount' => 0,
            'listingPrice' => 3000,
            'salePrice' => 3000,
            'profit' => 1425.00,
            'tax' => 19,
            'executionNumber' => 1,
            'paymentDetailsType' => 'software',
            'productGroup' => ['id' => 6, 'name' => 'ELO Funktionsmodule'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 540.00,
            'manufacturerNumber' => 'PNF-123',
            "versions" => [
                [
                    "name" => "1.6.1",
                ]
            ],
        ],
        [
            'name' => 'ELO XML Import',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'ELO XML Import',

            'discount' => 0,
            'listingPrice' => 500,
            'salePrice' => 500,
            'profit' => 150.00,
            'tax' => 19,
            'executionNumber' => 1,
            'paymentDetailsType' => 'software',
            'productGroup' => ['id' => 6, 'name' => 'ELO Funktionsmodule'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 18,
            'maintanencePrice' => 90.00,
            'manufacturerNumber' => 'PNF-123',
            "versions" => [
                [
                    "name" => "1.7.1",
                ]
            ],
        ],
        [
            'name' => 'ELO XC',
            'status' => 1,
            'recurringPayment' => 0,
            'description' => 'ELO E-Mail-Management pro Benutzer-, Ressourcen- oder Equipment-Postfach
(inkl. Archivierung von Public Folders, Journal und Shared Mailboxes)',
            'discount' => 0,
            'listingPrice' => 85,
            'salePrice' => 85,
            'profit' => 25.00,
            'tax' => 19,
            'executionNumber' => 1,
            'paymentDetailsType' => 'software',
            'productGroup' => ['id' => 6, 'name' => 'ELO Funktionsmodule'],
            'eloVersion' => ['id' => 3, 'name' => '21.3'],
            'maintanenceRate' => 19,
            'maintanencePrice' => 16.5,
            'manufacturerNumber' => 'PNF-123',
            "versions" => [
                [
                    "name" => "4.7.1",
                ]
            ],
        ],
        [
            'name' => 'Erstellung Projektzeitplan',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 4,
            'salePrice' => 200,
            'productCategoryId' => 1,
        ],
        [
            'name' => 'Termin- & Ressourcenplanung',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 2,
            'salePrice' => 100,
            'productCategoryId' => 1
        ],
        [
            'name' => 'Vorbereitung & Infrastrukturabnahme',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 2,
            'salePrice' => 100,
            'productCategoryId' => 1
        ],
        [
            'name' => 'Durchführung & Dokumentation Kick-Off',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 4,
            'salePrice' => 200,
            'productCategoryId' => 1
        ],
        [
            'name' => 'Anwenderschulung',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 30,
            'dailyRate' => 240,
            'quantity' => 4,
            'salePrice' => 120,
            'productCategoryId' => 3
        ],
        [
            'name' => 'Hands-On Training',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 30,
            'dailyRate' => 24,
            'quantity' => 4,
            'salePrice' => 120,
            'productCategoryId' => 3
        ],
        [
            'name' => 'Key User Schulung',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 30,
            'dailyRate' => 24,
            'quantity' => 4,
            'salePrice' => 12,
            'productCategoryId' => 3
        ],
        [
            'name' => 'Qualitätssicherung (interner Test)',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 400,
            'quantity' => 10,
            'salePrice' => 500,
            'productCategoryId' => 4
        ],
        [
            'name' => 'Qualitätssicherung (externer Test)',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 8,
            'salePrice' => 400,
            'productCategoryId' => 4
        ],
        [
            'name' => 'Technische Dokumentation',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 6,
            'salePrice' => 300,
            'productCategoryId' => 4
        ],
        [
            'name' => 'Projektkommunikation und -information',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 8,
            'salePrice' => 400,
            'productCategoryId' => 5
        ],
        [
            'name' => 'Überwachung und Aktualisierung Projektzeitplan',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 4,
            'salePrice' => 200,
            'productCategoryId' => 5
        ],
        [
            'name' => 'Projektdokumentation / Berichtswesen',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 2,
            'salePrice' => 100,
            'productCategoryId' => 5
        ],
        [
            'name' => 'Überwachung der Ergebnisse & Qualität',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 400,
            'quantity' => 8,
            'salePrice' => 400,
            'productCategoryId' => 5
        ],
        [
            'name' => 'Termin- & Ressourcenplanung',
            'status' => 1,
            'productGroup' => ['id' => 7, 'name' => 'Projekt Service'],
            'discount' => 0,
            'tax' => 19,
            'description' => '...',
            'paymentDetailsType' => 'service',
            'unit' => null,
            'hourlyRate' => 50,
            'dailyRate' => 40,
            'quantity' => 4,
            'salePrice' => 200,
            'productCategoryId' => 5
        ]

    ];

    const STARTING_VALUE = PHP_INT_MIN;
    const ENDING_VALUE = PHP_INT_MAX;

    const ELO_VERSION = [
        [
            'name' => '21.1',
            'type' => 'lts'
        ],
        [
            'name' => '21.2',
            'type' => 'lts'
        ],

        [
            'name' => '21.3',
            'type' => 'hf'
        ],
    ];

    const AFFECTED_GROUPS = [
        [
            'name' => 'employees',
        ],
        [
            'name' => 'customers',
        ],
    ];

    const PRODUCT_GROUPS = [
        ['name' => 'ELO BASIS'],
        ['name' => 'ELO Business Solution'],
        ['name' => 'ELO Integrations'],
        ['name' => 'DH Herolutions'],
        ['name' => 'DH Speed Basics'],
        ['name' => 'ELO Funktionsmodule'],
        ['name' => 'Projekt Service']
    ];

    const COMPANY = [
        [
            'companyName' => 'IPSEN',
            'customerType' => 'lead',
            'fax' => null,
            'id' => '1869d80a-65ee-5e33-010a-27ed6b7027fb',
            'invoiceEmail' => 'Dominik.Kilch@ipsen.de',
            'phone' => '+49 2821 804 – 391',
            'termsOfPayment' => null,
            'type' => 'premise',
            'url' => 'https://www.altus-ag.de',
            'vatId' => 'DE173867411',
            'addressFirst' => 'Flutstraße 78',
            'zip' => '47533',
            'city' => 'Kleve',
            'country' => 'Deutschland',
            'state' => 'Nordrhein-Westfalen',
            'isHeadQuarters' => 1,
            'status' => 'open'
        ],
        [
            'companyName' => 'ALTUS AG',
            'type' => 'premise',
            'url' => 'https://www.altus-ag.de',
            'addressFirst' => 'Kleinoberfeld 5',
            'city' => 'Karlsruhe',
            'zip' => '76135',
            'country' => 'Deutschland',
            'state' => 'Baden-Württemberg',
            'vatId' => 'HRB 703812',
            'phone' => '+49 (0)721 626 906 - 0',
            'customerType' => 'customer',
            'invoiceEmail' => 't.b.d.',
            'status' => 'open'
        ],
        [
            'companyName' => 'POLYRACK TECH-GROUP Holding GmbH & Co. KG',
            'type' => 'premise',
            'url' => 'https://www.polyrack.com',
            'addressFirst' => 'Steinbeisstrasse 4',
            'city' => 'Staubenhardt',
            'zip' => '75334',
            'country' => 'DE',
            'state' => 'Deutschland',
            'vatId' => 'DE 262 140 128',
            'phone' => '+49 (0)7082 7919-0',
            'customerType' => 'customer',
            'invoiceEmail' => 'Rechnung.PolyrackGmbH@polyrack.com',
            'status' => 'open'
        ],
        [
            'companyName' => 'Hittech PRONTOR GmbH',
            'type' => 'premise',
            'url' => 'https://hittech.com',
            'addressFirst' => 'Gauthierstraße 56',
            'city' => 'Bad Wildbad',
            'zip' => '75323',
            'country' => 'DE',
            'state' => 'Deutschland',
            'vatId' => 'DE 811 120 229',
            'phone' => '+49 7081 781 1',
            'customerType' => 'customer',
            'invoiceEmail' => 't.b.d.',
            'status' => 'open'
        ],
        [
            'companyName' => 'CANCOM GmbH',
            'type' => 'premise',
            'url' => 'https://www.cancom.de',
            'addressFirst' => 'Industriestraße 3',
            'city' => 'Stuttgart',
            'zip' => '70565',
            'country' => 'DE',
            'state' => 'Deutschland',
            'vatId' => 'DE 152 106 008',
            'phone' => '+49 89 54054-0',
            'customerType' => 'customer',
            'invoiceEmail' => 't.b.d.',
            'status' => 'open'
        ],
        [
            'companyName' => 'CHT Germany GmbH',
            'type' => 'premise',
            'url' => 'https://www.cht.com/',
            'addressFirst' => 'Bismarckstr. 102',
            'city' => 'Tübingen',
            'zip' => '72072',
            'country' => 'DE',
            'state' => 'Deutschland',
            'vatId' => 'DE 152 274 099',
            'phone' => '+ 49 7071 154 0',
            'customerType' => 'customer',
            'invoiceEmail' => 't.b.d.',
            'status' => 'open'
        ]
    ];

    const REPORT_CATEGORIES = [
        [
            'name' => 'Akquise',
        ],
        [
            'name' => 'Bestandskunden Management'
        ],

        [
            'name' => 'Neue Anfrage'
        ],
    ];

    //rdp file structutal constant
    const RDP_FILE_CONSTANT = "full address:s:%server_ip:%server_port\nprompt for credentials:i:0\nadministrative session:i:1\nusername:s:%user_name\npromptcredentialonce:i:1\ncredentials:i:0";


    //constants for nested array
    const KEY_SEPARATOR = '|';

    //auto index key

    const AUTO_INDEX_KEY = '[]';

    const PERMISSIONS = [
        1 => 'permission.list',
        'permission.create',
        'permission.edit',
        'permission.delete',
        //Role permissions
        'role.list',
        'role.create',
        'role.edit',
        'role.delete',
        //User permissions
        'user.list',
        'user.create',
        'user.edit',
        'user.delete',
        //Company permissions
        'company.list',
        'company.create',
        'company.edit',
        'company.delete',
        //Company employee permissions
        'company-employee.list',
        'company-employee.create',
        'company-employee.edit',
        'company-employee.delete',
        //Company location permissions
        'company-location.list',
        'company-location.create',
        'company-location.edit',
        'company-location.delete',
        //Report Category permissions
        'report-category.list',
        'report-category.create',
        'report-category.edit',
        'report-category.delete',
        //Contact Report permissions
        'contact-report.list',
        'contact-report.create',
        'contact-report.edit',
        'contact-report.delete',
        //ELO version permissions
        'elo-version.list',
        'elo-version.create',
        'elo-version.edit',
        'elo-version.delete',
        //Product permissions
        'product.list',
        'product.create',
        'product.edit',
        'product.delete',
        //Product Group permissions
        'product-group.list',
        'product-group.create',
        'product-group.edit',
        'product-group.delete',
        //Invoice permissions
        'invoice.list',
        'invoice.create',
        'invoice.edit',
        'invoice.delete',
        //Project permissions
        'project.list',
        'project.create',
        'project.edit',
        'project.delete',
        //Milestone permissions
        'milestone.list',
        'milestone.create',
        'milestone.edit',
        'milestone.delete',
        //Task permissions
        'task.list',
        'task.create',
        'task.edit',
        60 => 'task.delete',
        //My Tasks permissions
        75 => 'mytasks',
        //Self Service permissions
        174 => 'selfservice.list',
        175 => 'vacation-request.list',
        176 => 'travel-expenses.list',
        177 => 'asset-store.list',
        178 => 'my-asset.list',
        179 => 'personal-request.list',
        180 => 'modify-my-data.list',
        181 => 'access-to-personal-files.list',
        182 => 'invest-request.list',
        //System permissions
        61 => 'system.list',
        62 => 'system.create',
        63 => 'system.edit',
        64 => 'system.delete',
        //Time tracker permissions
        65 => 'time-tracker.list',
        66 => 'time-tracker.create',
        67 => 'time-tracker.edit',
        68 => 'time-tracker.delete',
        //Ticket permissions
        69 => 'ticket.list',
        70 => 'ticket.create',
        71 => 'ticket.edit',
        72 => 'ticket.delete',
        //Ticket comment permissions
        91 => 'ticket-comment.create',
        92 => 'ticket-comment.edit',
        93 => 'ticket-comment.delete',
        //product unit permission
        154 => 'product-unit.list',
        155 => 'product-unit.create',
        156 => 'product-unit.edit',
        157 => 'product-unit.delete',
        //Employee Vacation
        183 => 'employee-vacation.list',
        184 => 'employee-vacation.create',
        185 => 'employee-vacation.edit',
        186 => 'employee-vacation.delete',
        //Global Settings
        187 => 'global-setting.list',
        188 => 'global-setting.save',
        189 => 'elo-configuration.create',
        //Offer
        111 => 'offer.list',
        112 => 'offer.create',
        113 => 'offer.edit',
        114 => 'offer.delete',
        //Payment Period
        162 => 'product-period.list',
        163 => 'product-period.create',
        164 => 'product-period.edit',
        165 => 'product-period.delete',
        //Product Category
        119 => 'product-category.list',
        120 => 'product-category.create',
        121 => 'product-category.edit',
        122 => 'product-category.delete',
        //Product software
        158 => 'product-software.list',
        159 => 'product-software.create',
        160 => 'product-software.edit',
        161 => 'product-software.delete',
        //Supplier
        106 => 'supplier.list',
        107 => 'supplier.create',
        108 => 'supplier.edit',
        109 => 'supplier.delete',
        //Supplier location
        190 => 'supplier-location.list',
        191 => 'supplier-location.create',
        192 => 'supplier-location.edit',
        193 => 'supplier-location.delete',
        //Survey chapter
        194 => 'survey-chapter.create',
        195 => 'survey-chapter.edit',
        196 => 'survey-chapter.delete',
        197 => 'survey-configuration.list',
        198 => 'survey-configuration.create',
        199 => 'survey-configuration.delete',
        166 => 'system-host.list',
        167 => 'system-host.create',
        168 => 'system-host.edit',
        169 => 'system-host.delete',
        127 => 'terms-of-payment.list',
        128 => 'terms-of-payment.create',
        129 => 'terms-of-payment.edit',
        130 => 'terms-of-payment.delete',
        94 => 'user-department.list',
        95 => 'user-department.create',
        96 => 'user-department.edit',
        97 => 'user-department.delete',
        98 => 'user-location.list',
        99 => 'user-location.create',
        100 => 'user-location.edit',
        101 => 'user-location.delete',
        115 => 'user-profile.list',
        116 => 'user-profile.create',
        117 => 'user-profile.edit',
        118 => 'user-profile.delete',
        200 => 'user-profile-picture.show',
        201 => 'user-profile-picture.create',
        102 => 'user-team.list',
        103 => 'user-team.create',
        104 => 'user-team.edit',
        105 => 'user-team.delete',

        /* Template List */
        131 => 'template.list',
        132 => 'template.create',
        133 => 'template.edit',
        134 => 'template.delete',

        /*Performance Record System */

        144 => 'performance-record.list',
        145 => 'performance-record.create',
        146 => 'performance-record.edit',
        147 => 'performance-record.show',
        148 => 'performance-record.delete',

        /*Operating System */

        202 => 'operating-system.list',
        203 => 'operating-system.create',
        204 => 'operating-system.edit',
        205 => 'operating-system.delete',

        /*cover letter text  */

        206 => 'default-cover-letter-text.list',

        /*Lead Status  */

        207 => 'lead-status.list',
        208 => 'lead-status.create',
        209 => 'lead-status.edit',
        210 => 'lead-status.delete',

        /*Skill Management  */

        211 => 'skills.list',
        212 => 'skills.create',
        213 => 'skills.edit',
        214 => 'skills.delete',

        /*Fleet Cars  */

        215 => 'fleet-cars.list',
        216 => 'fleet-cars.create',
        217 => 'fleet-cars.edit',
        218 => 'fleet-cars.delete',

        /*Mileage Montoring  */

        219 => 'mileage-monitoring.list',

        /* Fuel Montoring  */
        220 => 'fuel-monitoring.list',
        221 => 'fuel-monitoring.create',

        /* Fleet Drivers  */
        222 => 'fleet-drivers.list',
        223 => 'fleet-drivers.create',
        224 => 'fleet-drivers.edit',
        /* Drivers Licence  */
        225 => 'drivers-licence.create',
        226 => 'drivers-licence.show',

        227 => 'fuel-monitoring.show',

        /* Sla */
        228 => 'sla.list',
        229 => 'sla.create',
        230 => 'sla.edit',
        240 => 'sla.delete',

        /* skill group */
        241 => 'skill-group.list',
        242 => 'skill-group.create',
        243 => 'skill-group.edit',
        244 => 'skill-group.delete',

        /* job level */
        245 => 'job-level.list',
        246 => 'job-level.create',
        247 => 'job-level.edit',
        248 => 'job-level.delete',

        /* jobs */
        249 => 'job.list',
        250 => 'job.create',
        251 => 'job.edit',
        252 => 'job.delete',

        /* finance dashboard */
        253 => 'finance-dashboard',
        254 => 'open-post.list',
        255 => 'consulting-dashboard',
        256 => 'handouts.list',
        257 => 'workshop-templates.list',
        258 => 'mail-template-assignment.list',
        259 => 'sales-dashboard',
        260 => 'consulting-teams.list',
        261 => 'consulting-teams.save',
        262 => 'changelog.list',
        263 => 'changelog.edit',
        264 => 'changelog.delete',

        // contract types
        265 => 'contract-types.list',
        266 => 'contract-types.create',
        267 => 'contract-types.edit',
        268 => 'contract-types.show',
        269 => 'contract-types.delete',

        // attachments
        270 => 'attachments.list',
        271 => 'attachments.create',
        272 => 'attachments.edit',
        273 => 'attachments.show',
        274 => 'attachments.delete',
        //template show
        275 => 'template.show',


        // order-confirmation
        276 => 'order-confirmation.list',
        277 => 'order-confirmation.create',
        278 => 'order-confirmation.edit',
        279 => 'order-confirmation.show',
        280 => 'order-confirmation.delete',

        // time checking
        281 => 'time-checking.list',
        282 => 'time-checking.update',

        // travel expenses
        283 => 'travel-expenses.create',
        284 => 'travel-expenses.edit',
        285 => 'travel-expenses.delete',

        // travel expense request type
        286 => 'travel-expense-request-type.list',
        287 => 'travel-expense-request-type.create',
        288 => 'travel-expense-request-type.edit',
        289 => 'travel-expense-request-type.delete',

        //outbounded contracts
        290 => 'outbounded-contracts.list',
        291 => 'outbounded-contracts.create',
        292 => 'outbounded-contracts.edit',
        293 => 'outbounded-contracts.delete',

        //affected groups
        294 => 'affected-groups.list',
        295 => 'affected-groups.create',
        296 => 'affected-groups.edit',
        297 => 'affected-groups.delete',

        //CIP Configuration
        298 => 'cip-configuration.list',
        299 => 'cip-configuration.save',

        //Continuous Improvement Process
        300 => 'continuous-improvement-process.list',
        301 => 'continuous-improvement-process.create',
        302 => 'continuous-improvement-process.edit',
        303 => 'continuous-improvement-process.delete',

        //Offer Requests
        304 => 'offer-requests.list',
        305 => 'offer-requests.create',
        306 => 'offer-requests.edit',
        307 => 'offer-requests.delete',

        //Creditor Invoices
        308 => 'creditor-invoices.list',
        309 => 'creditor-invoices.create',
        310 => 'creditor-invoices.edit',
        311 => 'creditor-invoices.delete',

        //Asset
        312 => 'asset.list',
        313 => 'asset.create',
        314 => 'asset.edit',
        315 => 'asset.delete',

        //asset employee list
        316 => 'asset-employee.list',
        317 => 'asset-employee.create',
        318 => 'asset-employee.edit',
        319 => 'asset-employee.delete',

        //Application Management Services
        320 => 'application-management-services.list',
        321 => 'application-management-services.create',
        322 => 'application-management-services.edit',
        323 => 'application-management-services.delete',

        //Invoice Templates
        324 => 'invoice-templates.list',
        325 => 'invoice-templates.create',
        326 => 'invoice-templates.edit',
        327 => 'invoice-templates.delete',

        //Project Protocol
        328 => 'project-protocols.list',
        329 => 'project-protocols.create',
        330 => 'project-protocols.edit',
        331 => 'project-protocols.delete',

        //SLA Levels
        332 => 'sla-levels.list',
        333 => 'sla-levels.create',
        334 => 'sla-levels.edit',
        335 => 'sla-levels.delete',

        //Product Store
        336 => 'product-store.list',
        337 => 'product-store.create',
        338 => 'product-store.edit',
        339 => 'product-store.delete',

        //Support Dashboard
        340 => 'support-dashbaord',

        //Global Notification user
        341 => 'global-notification.list',
        342 => 'global-notification.create',

        //Project Management Dashbaord
        343 => 'project-management-dashboard',

        //Project Management Dashbaord Teams
        344 => 'pm-dashboard-teams.list',
        345 => 'pm-dashboard-teams.save',

        //Planning Dashboard
        346 => 'planning-dashboard.list',

        //Travel Expense Invoice type
        347 => 'travel-expense-invoice-type.list',
        348 => 'travel-expense-invoice-type.create',
        349 => 'travel-expense-invoice-type.edit',
        350 => 'travel-expense-invoice-type.delete',

        //Skill Level
        351 => 'skill-level.list',
        352 => 'skill-level.create',
        353 => 'skill-level.edit',
        354 => 'skill-level.delete',

        //Review Report
        355 => 'review-report.list',
        356 => 'review-report.create',
        357 => 'review-report.edit',
        358 => 'review-report.delete',

        //employee interview
        359 => 'employee-interview.list',
        360 => 'employee-interview.create',
        361 => 'employee-interview.edit',
        362 => 'employee-interview.delete',

        //partner order
        363 => 'partner-order.list',
        364 => 'partner-order.create',
        365 => 'partner-order.edit',
        366 => 'partner-order.delete',

          //partner customers
        367 => 'partner-company.list',
        368 => 'partner-company.create',
        369 => 'partner-company.delete',
        370 => 'partner-company.edit',
        371 => 'partner-company.show',

        //partner ticket
        372 => 'partner-ticket.list',
        373 => 'partner-ticket.create',
        374 => 'partner-ticket.delete',
        375 => 'partner-ticket.edit',

        //partner ticket comment
        376 => 'partner-ticket-comment.create',
        377 => 'partner-ticket-comment.edit',
        378 => 'partner-ticket-comment.delete',

        379 => 'infrastructure-settings.list',
        380 => 'infrastructure-settings.create',
        381 => 'infrastructure-settings.edit',
        382 => 'infrastructure-settings.delete',

        383 => 'elo_business_solution.list',
        384 => 'elo_business_solution.create',
        385 => 'elo_business_solution.edit',
        386 => 'elo_business_solution.delete',


        387 => 'customer-portal-news.list',
        388 => 'customer-portal-news.create',
        389 => 'customer-portal-news.edit',
        390 => 'customer-portal-news.delete',

        //asset delivery list
        391 => 'asset-delivery.list',
        392 => 'asset-delivery.create',
        393 => 'asset-delivery.edit',
        394 => 'asset-delivery.delete',

    ];

    const USER_PROFILE_DATA = [
        [
            "user_id" => "1867617a-9ffa-ff35-025a-42baa91ca4d3",
            "email" => "t.schmidt-tudl@docshero.de",
            "first_name" => "Tobias",
            "last_name" => "Schmidt-Tudl",
        ],
    ];

    const UNITS_DATA = [
        [
            "name" => "pauschal",
            "shortName" => "pausch.",
        ],
        [
            "name" => "Stunden",
            "shortName" => "Std.",
        ],
        [
            "name" => "Menge",
            "shortName" => "Stk.",
        ]
    ];

    const PAYMENT_PERIOD_DATA = [
        [
            "name" => "einmalig",
            "billingCycle" => 0,
        ],
        [
            "name" => "monatlich",
            "billingCycle" => 30,
        ],
        [
            "name" => "quartalsweise",
            "billingCycle" => 90,
        ],
        [
            "name" => "halbjährlich",
            "billingCycle" => 180,
        ],
        [
            "name" => "jährlich",
            "billingCycle" => 365,
        ]
    ];

    const SOFTWARE = [
        [
            'name' => 'ELO'
        ]
    ];

    const VEHICLE_CLASSES = [
        ['name' => 'A1'],
        ['name' => 'A2'],
        ['name' => 'A'],
        ['name' => 'AM'],
        ['name' => 'B'],
        ['name' => 'BE'],
        ['name' => 'C1'],
        ['name' => 'C1E'],
        ['name' => 'C'],
        ['name' => 'CE'],
        ['name' => 'D1'],
        ['name' => 'D1E'],
        ['name' => 'D'],
        ['name' => 'DE'],
        ['name' => 'L'],
        ['name' => 'M'],
        ['name' => 'S'],
        ['name' => 'T'],
    ];

    const FORM_OF_CONTRACT = [
        [
            'name' => 'Unbefristet in Vollzeit',
        ],
        [
            'name' => 'Befristet in Vollzeit',
        ],
        [
            'name' => 'Unbefristet in Teilzeit',
        ],
        [
            'name' => 'Befristet in Teilzeit',
        ],
    ];
}
