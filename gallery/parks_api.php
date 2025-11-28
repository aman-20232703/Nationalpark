<?php
header('Content-Type: application/json');

// You can get a free API key from https://www.nps.gov/subjects/developer/get-started.htm
// For now, using demo data structure similar to NPS API

// Simulated National Park data with Indian parks
$parksData = [
    [
        'id' => 'ranthambore',
        'fullName' => 'Ranthambore National Park',
        'description' => 'One of the largest and most renowned national parks in Northern India, known for its tiger population and ancient fort ruins.',
        'states' => 'Rajasthan',
        'latitude' => '26.0173',
        'longitude' => '76.5026',
        'images' => [
            [
                'url' => 'Ranthambhore.jpg',
                'title' => 'Ranthambore Tiger Reserve',
                'altText' => 'Tiger in natural habitat'
            ]
        ],
        'activities' => ['Wildlife Watching', 'Photography', 'Safari Tours'],
        'contacts' => [
            'phoneNumbers' => ['+91-7462-220808'],
            'emailAddresses' => ['ranthambore@forest.rajasthan.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1500', 'description' => 'Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'October to June', 'standardHours' => ['wednesday' => '6:00AM - 9:30AM, 2:30PM - 6:00PM']]
        ],
        'directionsInfo' => 'Located 180 km from Jaipur, accessible by road and rail.',
        'weatherInfo' => 'Best season: October to April. Summer temperatures can exceed 45°C.',
        'wildlife' => 'Tigers',
        'visits' => 15000,
        'reviews' => 4500,
        'rating' => 4.8
    ],
    [
        'id' => 'kaziranga',
        'fullName' => 'Kaziranga National Park',
        'description' => 'A UNESCO World Heritage Site, home to two-thirds of the world\'s great one-horned rhinoceros population.',
        'states' => 'Assam',
        'latitude' => '26.5775',
        'longitude' => '93.1711',
        'images' => [
            [
                'url' => 'Kaziranga.jpg',
                'title' => 'Kaziranga Rhino Sanctuary',
                'altText' => 'One-horned rhinoceros'
            ]
        ],
        'activities' => ['Elephant Safari', 'Jeep Safari', 'Bird Watching'],
        'contacts' => [
            'phoneNumbers' => ['+91-3776-268095'],
            'emailAddresses' => ['kaziranga@forest.assam.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1200', 'description' => 'Elephant Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'November to April', 'standardHours' => ['wednesday' => '7:00AM - 10:00AM, 2:00PM - 4:30PM']]
        ],
        'directionsInfo' => '217 km from Guwahati, well connected by road and rail.',
        'weatherInfo' => 'Best season: November to April. Park closes during monsoon.',
        'wildlife' => 'Rhinos',
        'visits' => 12000,
        'reviews' => 3800,
        'rating' => 4.7
    ],
    [
        'id' => 'periyar',
        'fullName' => 'Periyar National Park',
        'description' => 'Famous for its scenic beauty and wildlife, especially elephants. Located in the Western Ghats.',
        'states' => 'Kerala',
        'latitude' => '9.4648',
        'longitude' => '77.2350',
        'images' => [
            [
                'url' => 'Periyar.JPG',
                'title' => 'Periyar Tiger Reserve',
                'altText' => 'Elephants by the lake'
            ]
        ],
        'activities' => ['Boat Safari', 'Nature Walk', 'Bamboo Rafting'],
        'contacts' => [
            'phoneNumbers' => ['+91-4869-222027'],
            'emailAddresses' => ['periyar@forest.kerala.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '800', 'description' => 'Boat Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'Open year-round', 'standardHours' => ['wednesday' => '6:00AM - 6:00PM']]
        ],
        'directionsInfo' => '140 km from Kochi, accessible by road through scenic routes.',
        'weatherInfo' => 'Pleasant throughout the year. Monsoon brings lush greenery.',
        'wildlife' => 'Elephants',
        'visits' => 18000,
        'reviews' => 5200,
        'rating' => 4.6
    ],
    [
        'id' => 'bandipur',
        'fullName' => 'Bandipur National Park',
        'description' => 'Part of the Nilgiri Biosphere Reserve, known for its rich biodiversity and tiger conservation.',
        'states' => 'Karnataka',
        'latitude' => '11.6667',
        'longitude' => '76.5833',
        'images' => [
            [
                'url' => 'bandipur.webp',
                'title' => 'Bandipur Tiger Reserve',
                'altText' => 'Wildlife in Bandipur'
            ]
        ],
        'activities' => ['Safari Tours', 'Bird Watching', 'Trekking'],
        'contacts' => [
            'phoneNumbers' => ['+91-8229-236041'],
            'emailAddresses' => ['bandipur@forest.karnataka.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1000', 'description' => 'Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'October to May', 'standardHours' => ['wednesday' => '6:30AM - 9:00AM, 3:30PM - 5:30PM']]
        ],
        'directionsInfo' => '80 km from Mysore, well connected by road.',
        'weatherInfo' => 'Best season: October to May. Avoid monsoon months.',
        'wildlife' => 'Tigers',
        'visits' => 11000,
        'reviews' => 3200,
        'rating' => 4.5
    ],
    [
        'id' => 'jim-corbett',
        'fullName' => 'Jim Corbett National Park',
        'description' => 'India\'s oldest national park, established in 1936. Famous for Bengal tigers and diverse wildlife.',
        'states' => 'Uttarakhand',
        'latitude' => '29.5319',
        'longitude' => '78.7647',
        'images' => [
            [
                'url' => 'Corbett_National_Park.jpg',
                'title' => 'Jim Corbett National Park',
                'altText' => 'Jim Corbett landscape'
            ]
        ],
        'activities' => ['Jeep Safari', 'Elephant Safari', 'Canter Safari'],
        'contacts' => [
            'phoneNumbers' => ['+91-5947-251489'],
            'emailAddresses' => ['corbett@forest.uttarakhand.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1500', 'description' => 'Jeep Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'Mid-October to Mid-June', 'standardHours' => ['wednesday' => '6:00AM - 10:00AM, 3:00PM - 6:00PM']]
        ],
        'directionsInfo' => '260 km from Delhi, accessible by road and rail.',
        'weatherInfo' => 'Best season: November to February. Pleasant weather.',
        'wildlife' => 'Tigers',
        'visits' => 20000,
        'reviews' => 6800,
        'rating' => 4.9
    ],
    [
        'id' => 'kanha',
        'fullName' => 'Kanha National Park',
        'description' => 'One of India\'s largest parks, inspiration for Rudyard Kipling\'s "The Jungle Book".',
        'states' => 'Madhya Pradesh',
        'latitude' => '22.2727',
        'longitude' => '80.6105',
        'images' => [
            [
                'url' => 'kanha.webp',
                'title' => 'Kanha Tiger Reserve',
                'altText' => 'Kanha grasslands'
            ]
        ],
        'activities' => ['Wildlife Safari', 'Nature Walks', 'Bird Watching'],
        'contacts' => [
            'phoneNumbers' => ['+91-7649-277267'],
            'emailAddresses' => ['kanha@forest.mp.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1400', 'description' => 'Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'October to June', 'standardHours' => ['wednesday' => '6:00AM - 11:00AM, 2:30PM - 5:30PM']]
        ],
        'directionsInfo' => '160 km from Jabalpur, accessible by road.',
        'weatherInfo' => 'Best season: October to June. Cool winters.',
        'wildlife' => 'Tigers',
        'visits' => 14000,
        'reviews' => 4100,
        'rating' => 4.8
    ],
    [
        'id' => 'gir',
        'fullName' => 'Gir Forest National Park',
        'description' => 'The only home of the Asiatic Lion in the world. A conservation success story.',
        'states' => 'Gujarat',
        'latitude' => '21.1248',
        'longitude' => '70.7898',
        'images' => [
            [
                'url' => 'gir.jpg',
                'title' => 'Gir Forest',
                'altText' => 'Asiatic Lions'
            ]
        ],
        'activities' => ['Lion Safari', 'Wildlife Photography', 'Nature Trails'],
        'contacts' => [
            'phoneNumbers' => ['+91-2877-285541'],
            'emailAddresses' => ['gir@forest.gujarat.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1600', 'description' => 'Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'October to June', 'standardHours' => ['wednesday' => '6:30AM - 9:00AM, 3:00PM - 5:30PM']]
        ],
        'directionsInfo' => '60 km from Junagadh, well connected by road.',
        'weatherInfo' => 'Best season: December to March. Avoid peak summer.',
        'wildlife' => 'Lions',
        'visits' => 13000,
        'reviews' => 3900,
        'rating' => 4.7
    ],
    [
        'id' => 'sundarbans',
        'fullName' => 'Sundarbans National Park',
        'description' => 'UNESCO World Heritage Site, world\'s largest mangrove forest and home to Royal Bengal Tigers.',
        'states' => 'West Bengal',
        'latitude' => '21.9497',
        'longitude' => '88.8831',
        'images' => [
            [
                'url' => 'sunderban.webp',
                'title' => 'Sundarbans Mangroves',
                'altText' => 'Mangrove forest'
            ]
        ],
        'activities' => ['Boat Safari', 'Bird Watching', 'Village Tours'],
        'contacts' => [
            'phoneNumbers' => ['+91-3218-252842'],
            'emailAddresses' => ['sundarbans@forest.wb.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1100', 'description' => 'Boat Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'September to March', 'standardHours' => ['wednesday' => '7:00AM - 5:00PM']]
        ],
        'directionsInfo' => '110 km from Kolkata, accessible by road and boat.',
        'weatherInfo' => 'Best season: November to February. Monsoon brings floods.',
        'wildlife' => 'Tigers',
        'visits' => 8000,
        'reviews' => 2400,
        'rating' => 4.4
    ],
    [
        'id' => 'valmiki',
        'fullName' => 'Valmiki National Park',
        'description' => 'The only national park in Bihar, part of the Indo-Nepal forest belt with diverse wildlife.',
        'states' => 'Bihar',
        'latitude' => '27.1833',
        'longitude' => '84.0333',
        'images' => [
            [
                'url' => 'valmiki.jpg',
                'title' => 'Valmiki Tiger Reserve',
                'altText' => 'Valmiki forest'
            ]
        ],
        'activities' => ['Safari Tours', 'Bird Watching', 'Nature Walks'],
        'contacts' => [
            'phoneNumbers' => ['+91-6255-232100'],
            'emailAddresses' => ['valmiki@forest.bihar.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '900', 'description' => 'Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'October to June', 'standardHours' => ['wednesday' => '6:00AM - 10:00AM, 3:00PM - 5:30PM']]
        ],
        'directionsInfo' => '100 km from Bettiah, accessible by road.',
        'weatherInfo' => 'Best season: November to May. Pleasant weather.',
        'wildlife' => 'Tigers',
        'visits' => 5500,
        'reviews' => 1800,
        'rating' => 4.3
    ],
    [
        'id' => 'nagarhole',
        'fullName' => 'Nagarhole National Park',
        'description' => 'Part of the Nilgiri Biosphere Reserve, known for its picturesque landscape and rich wildlife.',
        'states' => 'Karnataka',
        'latitude' => '12.0000',
        'longitude' => '76.1000',
        'images' => [
            [
                'url' => 'nagarhole.webp',
                'title' => 'Nagarhole National Park',
                'altText' => 'Nagarhole landscape'
            ]
        ],
        'activities' => ['Jeep Safari', 'Boat Rides', 'Wildlife Photography'],
        'contacts' => [
            'phoneNumbers' => ['+91-8228-264403'],
            'emailAddresses' => ['nagarhole@forest.karnataka.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '1000', 'description' => 'Safari per person']
        ],
        'operatingHours' => [
            ['description' => 'October to May', 'standardHours' => ['wednesday' => '6:30AM - 9:00AM, 3:30PM - 5:30PM']]
        ],
        'directionsInfo' => '90 km from Mysore, accessible by road.',
        'weatherInfo' => 'Best season: October to May. Cool and pleasant.',
        'wildlife' => 'Tigers',
        'visits' => 7000,
        'reviews' => 2100,
        'rating' => 4.6
    ],
    [
        'id' => 'hemis',
        'fullName' => 'Hemis National Park',
        'description' => 'The largest national park in South Asia, home to the endangered Snow Leopard.',
        'states' => 'Ladakh',
        'latitude' => '34.0000',
        'longitude' => '77.6667',
        'images' => [
            [
                'url' => 'hemis.webp',
                'title' => 'Hemis High Altitude Park',
                'altText' => 'Snow leopard habitat'
            ]
        ],
        'activities' => ['Snow Leopard Tracking', 'Trekking', 'Photography'],
        'contacts' => [
            'phoneNumbers' => ['+91-1982-252297'],
            'emailAddresses' => ['hemis@forest.ladakh.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '500', 'description' => 'Entry per person']
        ],
        'operatingHours' => [
            ['description' => 'May to September', 'standardHours' => ['wednesday' => '6:00AM - 6:00PM']]
        ],
        'directionsInfo' => '40 km from Leh, accessible by road.',
        'weatherInfo' => 'Best season: June to September. Extreme winters.',
        'wildlife' => 'Snow Leopard',
        'visits' => 5000,
        'reviews' => 1500,
        'rating' => 4.8
    ],
    [
        'id' => 'nanda-devi',
        'fullName' => 'Nanda Devi National Park',
        'description' => 'UNESCO World Heritage Site, surrounds India\'s second highest peak with pristine Himalayan ecosystem.',
        'states' => 'Uttarakhand',
        'latitude' => '30.3667',
        'longitude' => '79.8167',
        'images' => [
            [
                'url' => 'nandadevi.webp',
                'title' => 'Nanda Devi Sanctuary',
                'altText' => 'Nanda Devi peak'
            ]
        ],
        'activities' => ['Trekking', 'Mountaineering', 'Wildlife Observation'],
        'contacts' => [
            'phoneNumbers' => ['+91-1372-226179'],
            'emailAddresses' => ['nandadevi@forest.uttarakhand.gov.in']
        ],
        'entranceFees' => [
            ['cost' => '600', 'description' => 'Entry per person']
        ],
        'operatingHours' => [
            ['description' => 'May to October', 'standardHours' => ['wednesday' => '7:00AM - 5:00PM']]
        ],
        'directionsInfo' => 'Accessible from Joshimath, requires trekking.',
        'weatherInfo' => 'Best season: May to October. Closed in winter.',
        'wildlife' => 'Snow Leopard',
        'visits' => 4000,
        'reviews' => 1200,
        'rating' => 4.7
    ]
];

// Return JSON data
echo json_encode([
    'success' => true,
    'total' => count($parksData),
    'data' => $parksData
]);
