<?php

return [
    'nav' => [
        'home' => 'Sākums',
        'about' => 'Par mums',
        'works' => 'Mūsu darbi',
        'materials' => 'Materiāli',
    ],
    'actions' => [
        'calculate_price' => 'Aprēķināt cenu',
        'view_works' => 'Skatīt darbus',
        'submit_request' => 'Nosūtīt pieprasījumu',
        'submit_application' => 'Nosūtīt pieteikumu',
        'close_window' => 'Aizvērt logu',
        'back_to_top' => 'Atpakaļ uz sākumu',
    ],
    'works' => [
        'all' => 'Visi',
        'empty_category' => 'Šajā kategorijā darbu nav.',
    ],
    'work' => [
        'client' => 'Klients',
        'date' => 'Datums',
        'place' => 'Vieta',
        'type' => 'Tips',
        'explore_other_projects' => 'Iepazīstiet citus projektus',
    ],
    'calc' => [
        'thanks_title' => 'Paldies! Jūsu pieprasījums ir saņemts.',
        'invoice_text' => 'Uz jūsu e-pastu ir nosūtīts rēķins 50 € + PVN 21% apmērā par izmaksu aprēķina un tāmes sagatavošanu.',
        'invoice_note' => 'Pieprasījuma izskatīšanu uzsāksim pēc rēķina apmaksas. Ja nolemsiet noformēt pasūtījumu, šī summa tiks atskaitīta no kopējās cenas.',
        'waiting' => 'Pagaidiet...',
        'send_failed' => 'Neizdevās nosūtīt formu. Lūdzu, mēģiniet vēlreiz.',
        'type' => 'Tips',
        'sizes' => 'Izmēri (mm)',
        'your_data' => 'Jūsu dati',
        'approx_price' => 'Orientējošā cena',
        'note' => 'Norādītā cena ir provizoriska un nav gala cena. Gala izmaksas tiek noteiktas pēc detalizēta aprēķina un projekta saskaņošanas.',
        'validation' => [
            'category' => [
                'required' => 'Lūdzu, izvēlieties kategoriju.',
                'max' => 'Kategorijas vērtība ir pārāk gara.',
            ],
            'width' => [
                'required' => 'Lūdzu, norādiet platumu.',
                'numeric' => 'Platumam jābūt skaitlim.',
                'min' => 'Platumam jābūt vismaz 0.1 mm.',
            ],
            'height' => [
                'required' => 'Lūdzu, norādiet augstumu.',
                'numeric' => 'Augstumam jābūt skaitlim.',
                'min' => 'Augstumam jābūt vismaz 0.1 mm.',
            ],
            'depth' => [
                'required' => 'Lūdzu, norādiet dziļumu.',
                'numeric' => 'Dziļumam jābūt skaitlim.',
                'min' => 'Dziļumam jābūt vismaz 0.1 mm.',
            ],
            'full_name' => [
                'required' => 'Lūdzu, norādiet pilnu vārdu.',
                'max' => 'Vārds nevar pārsniegt 255 rakstzīmes.',
            ],
            'email' => [
                'required' => 'Lūdzu, norādiet e-pastu.',
                'email' => 'Nepareizs e-pasta formāts.',
                'max' => 'E-pasts nevar pārsniegt 255 rakstzīmes.',
            ],
            'address' => [
                'required' => 'Lūdzu, norādiet adresi.',
                'max' => 'Adrese nevar pārsniegt 255 rakstzīmes.',
            ],
            'form' => [
                'send_failed' => 'Neizdevās nosūtīt e-pastu. Lūdzu, mēģiniet vēlreiz.',
                'success' => 'Pieteikums nosūtīts veiksmīgi.',
            ],
        ],
    ],
    'common' => [
        'share_with_friends' => 'Pastāsti draugiem',
        'home_dash' => 'Sākums -',
    ],
    'footer' => [
        'contact_us' => 'Sazināties ar mums',
        'address' => 'Adrese',
        'phone' => 'Telefons',
        'email' => 'E-pasts',
        'get_directions' => 'Iegūt norādes',
        'sections' => 'Sadaļas',
        'social' => 'Sociālie tīkli',
        'copyright' => '© 2025 Anselat SIA Visas tiesības aizsargātas',
    ],
    'request' => [
        'address' => 'Jūsu adrese',
        'phone' => 'Jūsu tālruņa numurs',
        'whatsapp_hint' => 'Atzīmējiet, ja šim numuram ir WhatsApp, un vēlaties saņemt atbildi tur.',
    ],
];
