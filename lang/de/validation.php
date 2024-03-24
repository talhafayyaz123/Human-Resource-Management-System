<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Die AGB müssen akzeptiert werden',
    'active_url' => 'Das :attribute ist keine gültige URL.',
    'after' => 'Das :attribute muss ein Datum nach :date.',
    'alpha' => 'Das :attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => 'Das :attribute darf nur Buchstaben, Zahlen und Bindestriche enthalten.',
    'alpha_num' => 'Das :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array' => 'Das :attribute muss ein Array sein.',
    'before' => 'Das :attribute muss ein Datum vor :date.',
    'before_or_equal' => 'Das :attribute muss ein Datum vor oder gleich dem :date.',
    'between' =>
    [
        'array' => 'Das :attribute muss folgende Werte haben :min - :max .',
        'file' => 'Das :attribute muss zwischen :min - :max Kilobyte liegen.',
        'numeric' => 'Das :attribute muss zwischen :min - :max.',
        'string' => 'Das :attribute muss zwischen :min - :max Zeichen.',
    ],
    'confirmed' => 'Die eingegeben :attribute stimmen nicht überein',
    'date' => 'Das :attribute ist kein gültiges Datum.',
    'date_difference_less' => 'Der Unterschied in Tagen muss sein :days',
    'date_format' => 'Das :attribute entspricht nicht dem Format :format.',
    'different' => 'Das :attribute und das :other müssen unterschiedlich sein.',
    'digits' => 'Das :attribute muss sein :digits Ziffern.',
    'digits_between' => 'Die :attribute muss mindestens +:min Zeichen haben.',
    'distinct' => 'Das Feld :attribute hat einen doppelten Wert.',
    'email' => 'Das :attribute ist ungültig.',
    'email_advanced' => 'Das :attribute ist keine gültige E-Mail',
    'email_advanced_with_external' => 'Das :attribute ist ungültig oder die E-Mail existiert nicht',
    'exists' => 'Das :attribute selected :attribute ist ungültig.',
    'field_class_not_found' => 'FEHLER: FELDKLASSE NICHT GEFUNDEN',
    'image' => 'Das :attribute muss ein Bild sein.',
    'in' => 'Das :attribute selected :attribute ist ungültig.',
    'in_array' => 'Das Feld :attribute existiert nicht in :other.',
    'integer' => 'Das :attribute muss eine Ganzzahl sein.',
    'ip' => 'Das :attribute muss eine gültige IP-Adresse sein.',
    'max' =>
    [
        'array' => 'Das :attribute darf nicht mehr als :max Elemente haben.',
        'file' => 'Das :attribute darf nicht größer als :max Kilobyte sein.',
        'numeric' => 'Das :attribute darf nicht größer sein als :max.',
        'string' => 'Das :attribute darf nicht größer als :max Zeichen sein.',
    ],
    'mimes' => 'Das :attribute muss eine Datei vom Typ :values.',
    'min' =>
    [
        'array' => 'Das :attribute muss mindestens :min .',
        'file' => 'Das :attribute muss mindestens :min Kilobyte.',
        'numeric' => 'Das :attribute muss mindestens :min.',
        'string' => 'Das :attribute muss mindestens :min Zeichen sein.',
    ],
    'multiple' => 'Das :attribute muss ein Vielfaches von :multiple',
    'not_in' => 'Das :attribute selected :attribute ist ungültig.',
    'numeric' => 'Das :attribute muss eine Zahl sein.',
    'phone' => 'Das :attribute ist kein gültiges Telefon',
    'present' => 'Das :attribute muss vorhanden sein.',
    'recaptcha' => 'Das Feld :attribute ist nicht korrekt.',
    'regex' => 'Das :attribute ist ungültig.',
    'required' => 'Das Feld :attribute ist erforderlich.',
    'required_if' => 'Das Feld :attribute wird benötigt, wenn :other ist :value.',
    'required_unless' => 'Das :attribute ist erforderlich, es sei denn :other ist in :values.',
    'required_with' => 'Das :attribute ist erforderlich, wenn :values vorhanden sind.',
    'required_without' => 'Das :attribute ist erforderlich, wenn :values nicht vorhanden sind.',
    'required_without_all' => 'Das :attribute ist erforderlich, wenn :values nicht vorhanden sind.',
    'same' => 'Das :attribute und das :other müssen übereinstimmen.',
    'size' =>
    [
        'array' => 'Das :attribute muss Folgendes enthalten :size Größenelemente.',
        'file' => 'Das :attribute muss folgende :size Kilobyte.',
        'numeric' => 'Das :attribute muss Folgendes sein :size.',
        'string' => 'Das :attribute muss Zeichen der :size .',
    ],
    'time' => 'Das :attribute entspricht nicht dem Format HH: MM.',
    'unique' => 'Das :attribute wurde bereits vergeben.',
    'url' => 'Das :attribute ist ungültig.',

    'attributes' => [],
];
