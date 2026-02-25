<?php

return [
    'nav' => [
        'home' => 'Home',
        'about' => 'About us',
        'works' => 'Our works',
        'materials' => 'Materials',
    ],
    'actions' => [
        'calculate_price' => 'Calculate price',
        'view_works' => 'View works',
        'submit_request' => 'Send request',
        'submit_application' => 'Submit application',
        'close_window' => 'Close window',
        'back_to_top' => 'Back to top',
    ],
    'works' => [
        'all' => 'All',
        'empty_category' => 'No works found for this category.',
    ],
    'work' => [
        'client' => 'Client',
        'date' => 'Date',
        'place' => 'Place',
        'type' => 'Type',
        'explore_other_projects' => 'Explore other projects',
    ],
    'calc' => [
        'thanks_title' => 'Thank you! Your request has been received.',
        'invoice_text' => 'An invoice for 50 € + VAT 21% has been sent to your email for cost estimation and quote preparation.',
        'invoice_note' => 'We will start processing your request after payment. If you place an order, this amount will be deducted from the total price.',
        'waiting' => 'Please wait...',
        'send_failed' => 'Failed to send the form. Please try again.',
        'type' => 'Type',
        'sizes' => 'Dimensions (mm)',
        'your_data' => 'Your details',
        'approx_price' => 'Estimated price',
        'note' => 'The indicated price is approximate and not final. Final costs are determined after detailed calculation and project approval.',
        'validation' => [
            'category' => [
                'required' => 'Please choose a category.',
                'max' => 'Category value is too long.',
            ],
            'width' => [
                'required' => 'Please enter width.',
                'numeric' => 'Width must be a number.',
                'min' => 'Width must be at least 0.1 mm.',
            ],
            'height' => [
                'required' => 'Please enter height.',
                'numeric' => 'Height must be a number.',
                'min' => 'Height must be at least 0.1 mm.',
            ],
            'depth' => [
                'required' => 'Please enter depth.',
                'numeric' => 'Depth must be a number.',
                'min' => 'Depth must be at least 0.1 mm.',
            ],
            'full_name' => [
                'required' => 'Please enter full name.',
                'max' => 'Full name may not exceed 255 characters.',
            ],
            'email' => [
                'required' => 'Please enter email.',
                'email' => 'Invalid email format.',
                'max' => 'Email may not exceed 255 characters.',
            ],
            'address' => [
                'required' => 'Please enter address.',
                'max' => 'Address may not exceed 255 characters.',
            ],
            'form' => [
                'send_failed' => 'Failed to send email. Please try again.',
                'success' => 'Request sent successfully.',
            ],
        ],
    ],
    'common' => [
        'share_with_friends' => 'Share with friends',
        'home_dash' => 'Home -',
    ],
    'footer' => [
        'contact_us' => 'Contact us',
        'address' => 'Address',
        'phone' => 'Phone',
        'email' => 'Email',
        'get_directions' => 'Get directions',
        'sections' => 'Sections',
        'social' => 'Social networks',
        'copyright' => '© 2025 Anselat SIA All rights reserved',
    ],
    'request' => [
        'address' => 'Your address',
        'phone' => 'Your phone number',
        'whatsapp_hint' => 'Tick if this number has WhatsApp and you prefer a reply there.',
    ],
];
