<?php

return [

    'insurance_scan_path' => env('INSURANCE_SCAN_PATH', 'C:\\Assurances_dossier'),

    'al_wafaa_label' => 'الوفاء',

    'tesseract_binary' => env('TESSERACT_BINARY', 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'),

    'ghostscript_binary' => env('GHOSTSCRIPT_BINARY', 'C:\\Program Files\\gs\\gs10.07.0\\bin\\gswin64c.exe'),

    'gemini_model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),

    'groq_model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),

];