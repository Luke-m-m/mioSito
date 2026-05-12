<?php

$roomsData = [];

$adjectives = [
    "Eco Luxury", "Forest", "Ocean", "Bamboo", "Solar", "Rainforest",
    "Zen", "Green Leaf", "Earth", "Natural", "Sky", "Pure", "Wild",
    "Organic", "Nature", "Eco Breeze"
];

$features = [
    "con vista su giardini verticali",
    "immersa nella vegetazione tropicale",
    "alimentata da energia solare",
    "con materiali completamente riciclati",
    "con isolamento naturale in sughero",
    "con ventilazione naturale continua",
    "con arredamento in bambù certificato",
    "con grandi vetrate panoramiche",
    "con sistema di recupero acqua piovana",
    "con atmosfera zen rilassante"
];

$servicesPool = [
    "Wi-Fi eco ad alta velocità",
    "Illuminazione LED intelligente",
    "Climatizzazione naturale",
    "Bagno a basso consumo idrico",
    "Letti organici premium",
    "Arredi sostenibili certificati",
    "Vista giardino verticale",
    "Pulizia eco-friendly giornaliera",
    "Minibar biologico",
    "Riciclo completo rifiuti in camera"
];

for ($i = 1; $i <= 72; $i++) {

    $a = $adjectives[$i % count($adjectives)];
    $f = $features[$i % count($features)];

    // descrizione unica per ogni camera
    $roomsData[$i] = [
        "desc" => "La $a Suite #$i è una camera eco-sostenibile $f, progettata per offrire comfort moderno nel pieno rispetto della natura e dell’ambiente.",
        "services" => [
            $servicesPool[$i % count($servicesPool)],
            $servicesPool[($i+1) % count($servicesPool)],
            $servicesPool[($i+2) % count($servicesPool)],
            $servicesPool[($i+3) % count($servicesPool)],
            $servicesPool[($i+4) % count($servicesPool)],
        ]
    ];
}

?>