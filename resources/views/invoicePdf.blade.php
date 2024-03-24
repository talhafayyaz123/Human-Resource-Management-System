<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto:wght@500&display=swap"
        rel="stylesheet">
    <link href="{{ Vite::asset('resources/css/app.css') }}" rel="stylesheet">
    <!--import font -->

    <title></title>
    <style>
        @page {
            margin: 100px 90px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 80px;
        }

        .invoice-container {
            margin-bottom: 30px;
            max-width: 100%;
            margin-right: auto;
            margin-left: auto;
        }

        .text-right {
            font-size: 25px;
            font-weight: bold;
            font-family: 'Open Sans', sans-serif;
            float: right;
        }

        .word-docs {
            color: rgb(237, 125, 49)
        }

        .word-hero {
            color: rgb(41, 87, 164);
        }

        .company-details {
            font-family: 'Open Sans', sans-serif;
            font-size: 10px;
            font-weight: 500;
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .business-details {
            font-family: 'Open Sans', sans-serif;
            font-size: 13px;
            font-weight: 400;
        }

        .business-details-text {
            margin-top: 5px;
        }

        .invoice-details {
            margin-top: 50px;
        }

        .invoice-number {
            font-family: 'Open Sans', sans-serif;
            font-size: 20px;
            font-weight: 500;
        }

        .docs-hero-image {
            display: inline-block;
        }

        .word-docs-div {
            display: inline-block;
            margin-bottom: 10px;

        }

        .borderless td {
            border: 0;
        }

        .borderless {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }

        .docs-hero-footer-heading {
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            font-size: 12px
        }

        .docs-hero-footer-columns {
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            font-size: 12px;
            color: rgb(41, 87, 164);
        }

        .invoice-details-table {
            margin-top: 30px;
        }

        .product-details-table {
            margin-top: 50px;
        }

        .borderless th {
            text-align: left;
        }

        .give-padding-bottom {
            padding-bottom: 1em;
        }

        .total-amount-class {
            float: right;
            display: block;
        }

        .total-amount-class-final {
            margin-top: 10px;
            display: block;
        }

        .align-table-right {
            width: 35%;
            margin-right: 0px;
            margin-left: auto;
            margin-top: 20px;
            border-collapse: collapse;
            table-layout: fixed;
        }
    </style>
</head>

<body>
    <header>
        <div class="text-right">
            <img class="docs-hero-image" src="{{ public_path('images/hero.jpg') }}" width="70px">
            <div class="word-docs-div"> <span class="word-docs">Docs</span><span class="word-hero">Hero</span> </div>
        </div>
    </header>
    <footer>
        <table class="borderless">
            <tr>
                <td width="30%" class="docs-hero-footer-heading word-hero"><b>DocsHero GmbH</b></td>
                <td width="40%" class="docs-hero-footer-heading word-hero"><b>Geschäftsführer:</b></td>
                <td width="30%" class="docs-hero-footer-heading word-hero"><b>Volksbank pur eG</b></td>
            </tr>
            <tr>
                <td width="30%" class="docs-hero-footer-columns">Lorenzstraße 29</td>
                <td width="40%" class="docs-hero-footer-columns">Marcel Rupprecht, Tobias Schmidt-Tudl
                </td>
                <td width="30%" class="docs-hero-footer-columns">IBAN: DE96 6619 0000 0096 0162 55</td>
            </tr>
            <tr>
                <td width="30%" class="docs-hero-footer-columns">76135 Karlsruhe</td>
                <td width="40%" class="docs-hero-footer-columns"></td>
                <td width="30%" class="docs-hero-footer-columns">BIC: GENODE61KA1</td>
            </tr>
            <tr>
                <td width="30%" class="docs-hero-footer-columns">Tel.: +49 721 181205 00</td>
                <td width="40%" class="docs-hero-footer-columns">Amtsgericht Mannheim HRB 745699</td>
            </tr>
            <tr>
                <td width="30%" class="docs-hero-footer-columns">info@docshero.com</td>
            </tr>
            <tr>
                <td width="30%" class="docs-hero-footer-columns">www.docshero.de</td>
            </tr>

        </table>
    </footer>
    <main>
        <div class="invoice-container">
            <div class="company-details">
                <b> DocsHero GmbH</b> | Lorenzstraße 29 | 76135 Karlsruhe
            </div>
            <div class="business-details">
                <div class="business-details-text">
                    Firma
                </div>
                <div class="business-details-text">
                    ALTUS Aktiengesellschaft
                </div>
                <div class="business-details-text">
                    Kleinoberfeld 5
                </div>
                <div class="business-details-text">
                    DE- 76135 Karlsruhe
                </div>
            </div>
            <div class="invoice-details">
                <div class="invoice-number word-docs">
                    <b> Rechnung {{$invoice->invoice_number}} </b>
                </div>
            </div>
            <div class="invoice-details-table">
                <table class="borderless">
                    <tr>
                        <td width="5%" class="docs-hero-footer-heading word-hero"><b>Kunden-Nr</b></td>
                        <td width="10%" class="docs-hero-footer-heading word-hero">
                            {{$invoice->company->company_number}}
                        </td>
                        <td width="8%" class="docs-hero-footer-heading word-hero">
                            <b>Bearbeiter</b>
                        </td>
                        <td width="10%" class="docs-hero-footer-heading word-hero">Tobias
                            Schmidt-Tudl
                        </td>
                    </tr>
                    <tr>
                        <td width="5%" class="docs-hero-footer-heading word-hero"></td>
                        <td width="10%" class="docs-hero-footer-heading word-hero">

                        </td>
                        <td width="8%" class="docs-hero-footer-heading word-hero">
                            <b>Durchwahl</b>
                        </td>
                        <td width="10%" class="docs-hero-footer-heading word-hero">+49 157 8745 8725
                        </td>
                    </tr>
                    <tr>
                        <td width="5%" class="docs-hero-footer-heading word-hero"><b>Rechnungs-Nr</b></td>
                        <td width="10%" class="docs-hero-footer-heading word-hero">
                            {{$invoice->invoice_number}}
                        </td>
                        <td width="8%" class="docs-hero-footer-heading word-hero">
                            <b>Rechnungsdatum</b>
                        </td>
                        <td width="10%" class="docs-hero-footer-heading word-hero">
                            {{ Carbon\Carbon::now()->format('d-m-Y'); }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="product-details-table">
                <table class="borderless">
                    <tr>
                        <th width="10%" class="docs-hero-footer-heading">
                            <b>Pos</b>
                        </th>
                        <th width="30%" class="docs-hero-footer-heading">
                            <b>Bezeichnung</b>
                        </th>
                        <th style="text-align: right" width="10%" class="docs-hero-footer-heading">
                            <b>Menge/ME</b>
                        </th>
                        <th style="text-align: right" width="10%" class="docs-hero-footer-heading">
                            <b>Rabatt</b>
                        </th>
                        <th style="text-align: right" width="20%" class="docs-hero-footer-heading">
                            <b>Einzelpreis €</b>
                        </th>
                        <th style="text-align: right" width="20%" class="docs-hero-footer-heading">
                            <b>Gesamtpreis €</b>
                        </th>
                    </tr>
                    <tr style="border-bottom:1px solid black;">
                        <td colspan="100%"></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:.5em;" colspan="100%"></td>
                    </tr>
                    @if (isset($invoice->products))
                    @foreach ($invoice->products as $product)
                    <tr>
                        <td class="docs-hero-footer-heading give-padding-bottom">{{$loop->iteration}}</td>
                        <td class="docs-hero-footer-heading give-padding-bottom"><b>{{$product->name}}</b>
                            <br />
                            {{$product->description}}
                        </td>
                        <td style="text-align: right" class="docs-hero-footer-heading give-padding-bottom">
                            {{$product->pivot->quantity}} Stk</td>
                        <td style="text-align: right" class="docs-hero-footer-heading give-padding-bottom">
                            {{$product->pivot->discount}}%</td>
                        <td style="text-align: right" class="docs-hero-footer-heading give-padding-bottom">
                            {{$product->pivot->sale_price}}€</td>
                        <td style="text-align: right" class="docs-hero-footer-heading give-padding-bottom">
                            {{$product->pivot->price_without_tax}}€</td>
                    </tr>
                    @endforeach
                    <tr style="border-bottom:1px solid black;">
                        <td colspan="100%"></td>
                    </tr>
                    @endif

                </table>


                <table class="align-table-right">
                    <tr>
                        <td width="30%" style="text-align: right" class="docs-hero-footer-heading">Nettobetrag</td>
                        <td width="30%" style="text-align: right" class="docs-hero-footer-heading">
                            {{$invoice->total_amount_without_tax}}</td>
                    </tr>
                    <tr>
                        <td width="30%" style="text-align: right" class="docs-hero-footer-heading">zzgl. 19% MwSt.</td>
                        <td width="30%" style="text-align: right" class="docs-hero-footer-heading">
                            {{$invoice->total_tax_amount}}</td>
                    </tr>
                    <tr>
                        <td colspan="100%"></td>
                    </tr>
                    <tr>
                        <td colspan="100%"></td>
                    </tr>
                    <tr style="background-color:beige">
                        <td width="50%" style="text-align: right;" class="docs-hero-footer-heading">
                            <b>Rechnungsbetrag</b>
                        </td>
                        <td width="50%" style="text-align: right;" class="docs-hero-footer-heading">
                            <b>{{$invoice->total_amount}}</b>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="margin-top: 30px" class="last-invoice-column">
                <div class="docs-hero-footer-heading"><b>Zahlungsbedingungen:</b></div>
                <div class="docs-hero-footer-heading">[Zahlungsbedingungen]</div>

                <div style="margin-top: 20px;" class="docs-hero-footer-heading">
                    Die Ware bleibt bis zur Vollständigen Bezahlung unser Eigentum.
                </div>
                <div class="docs-hero-footer-heading">
                    Leistungsdatum entspricht Rechnungsdatum sofern nicht anders angegeben.
                </div>
                <div style="margin-top: 20px;" class="docs-hero-footer-heading">
                    Die Rechnung wurde maschinell erstellt und ist ohne Unterschrift rechtsgültig.
                </div>
            </div>
        </div>
    </main>
</body>

</html>