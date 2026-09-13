<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{$booking->show_booking_id}}</title>

    <style>
        @page {
            margin: 5mm 6mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #000;
            line-height: 1.3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            vertical-align: top;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .blue-bg {
            background: #d4af37;
            color: #fff;
        }

        .header-table {
            margin-bottom: 8px;
        }

        .header-table td {
            font-size: 18px;
            font-weight: bold;
        }

        .company-title {
            background: #d4af37;
            color: #fff;
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            padding: 10px 0;
            letter-spacing: 1px;
            line-height: 1;
        }

        .company-box {
            padding: 0px !important;
            border: none;
        }

        .company-left {
            width: 75%;
            padding: 8px 12px 8px 0;
        }



        .company-right {
            width: 25%;
            border-left: 2px solid #d4af37;
            text-align: center;
            vertical-align: middle;
        }

        .company-right img {
            width: 180px;
        }

        .company-row {
            margin-bottom: 8px;
            font-size: 17px;
            line-height: 1.2;
        }

        .company-row:last-child {
            margin-bottom: 0;
        }

        .company-row strong {
            display: inline-block;
            width: 110px;
        }

        .section-title {
            margin-top: 10px;
            background: #d4af37;
            color: white;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            padding: 8px 0;
        }

        .guest-table {
            border: 1px solid #444;
        }

        .guest-table td {
            border: 1px solid #444;
            padding: 8px;
            font-size: 16px;
        }

        .guest-label {
            width: 30%;
            font-weight: bold;
        }

        .guest-value {
            width: 70%;
        }

        .charges-table {
            margin-top: 20px;
            border: 2px solid #d4af37;
        }

        .charges-table th {
            background: #d4af37;
            color: #fff;
            border: 1px solid #ffffff;
            padding: 8px;
            text-align: center;
            font-size: 15px;
        }

        .charges-table td {
            border: 1px solid #d4af37;
            padding: 8px;
            font-size: 15px;
        }

        .item-row td {
            height: 50px !important;
            vertical-align: top;
            border-top: 0px;
            border-bottom:0px;
        }

        .amount-box {
            border: 2px solid #d4af37;
            border-top: none;
        }

        .amount-box td {
            border: 1px solid #d4af37;
        }

        .amount-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            padding-top: 8px;
            line-height: 1.2;
        }

        .amount-value {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding-top: 8px;
            line-height: 1.2;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            border: 1px solid #d4af37;
            padding: 5px 8px;
            font-size: 15px;
            line-height: 1.2;
        }

        .summary-label {
            font-weight: bold;
            width: 65%;
        }

        .summary-value {
            text-align: right;
            width: 35%;
        }

        .invoice-note {
            margin-top: 28px;
            font-size: 14px;
            line-height: 1.5;
        }

        .invoice-note strong {
            font-size: 15px;
        }

        .signature-table {
            margin-top: 30px;
        }

        .signature-box {
            width: 180px;
            height: 55px;
            border: 2px solid #d4af37;
        }

        .signature-label {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .left-sign {
            text-align: left;
        }

        .right-sign {
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td class="text-left">Date: {{ now()->format('d/m/Y') }} </td>
            <td class="text-right">Bill Number: {{$booking->show_booking_id}} </td>
        </tr>
    </table>

    <!-- COMPANY TITLE -->
    <table>
        <tr>
            <td class="company-title"> {{ strtoupper(setting_value('company_name')) }} </td>
        </tr>
    </table>

    <!-- COMPANY DETAILS -->
    <table class="company-box">
        <tr>
            <td class="company-left">
                <table style="width:100%; border:none;">
                    <tr>
                        <td style="width:110px; font-weight:bold; font-size:17px; vertical-align:top; padding:0 0 8px 0;">
                            Address:
                        </td>

                        <td style="font-size:17px; vertical-align:top; padding:0 0 8px 0;">
                            {{ $booking->property->branch->name . ' ' .
                            $booking->property->branch->location . ', ' .
                            $booking->property->branch->city->name . ', ' .
                            $booking->property->branch->city->state . ' - ' .
                            $booking->property->branch->pincode }}
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight:bold; font-size:17px; padding:0 0 8px 0;">
                            Phone No:
                        </td>

                        <td style="font-size:17px; padding:0 0 8px 0;">
                            {{ setting_value('company_phone') }}
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight:bold; font-size:17px; padding:0;">
                            Email Id:
                        </td>

                        <td style="font-size:17px; padding:0;">
                            {{ setting_value('company_email') }}
                        </td>
                    </tr>

                </table>

            </td>

            <td class="company-right">
                <img src="{{ base_path('storage/' .setting_value('company_large_logo')) }}" height="100px" width="100px" />
            </td>
        </tr>
    </table>

    <!-- BILLING TITLE -->
    <table>
        <tr>
            <td class="section-title"> Billing To</td>
        </tr>
    </table>

    <!-- GUEST DETAILS -->
    <table class="guest-table">
        @php
            $guests = $booking->guests->toArray();
        @endphp
        <tr>
            <td class="guest-label">Customer Name</td>

            <td class="guest-value">
                {{$guests[0]['name']}} @if(count($guests) > 1) & {{$guests[1]['name']}} @endif 
            </td>
        </tr>

        <tr>
            <td class="guest-label">Check-in</td>
            <td class="guest-value">
                {{date('d/M/Y', strtotime($booking->check_in))}} @ {{date('h:i A', strtotime($booking->check_in))}}
            </td>
        </tr>

        <tr>
            <td class="guest-label">Check-out</td>
            <td class="guest-value">
                {{date('d/M/Y', strtotime($booking->check_out))}} @ {{date('h:i A', strtotime($booking->check_out))}}
            </td>
        </tr>

        <tr>
            <td class="guest-label">ID Proof</td>
            <td class="guest-value">Aadhar / DL</td>
        </tr>

    </table>

    <table class="charges-table">

        <thead>
            <tr>
                <th style="width:14%;">Unit No</th>
                <th style="width:34%;">Particulars</th>
                <th style="width:17%;">Price Per<br>Day</th>
                <th style="width:15%;">Quantity</th>
                <th style="width:20%;">Amount</th>
            </tr>
        </thead>

        <tbody>
            @php
                $totalRows = 5;
                $currentRow = 0;
            @endphp
            <tr class="item-row">
                <td class="text-center">
                    {{$booking->property->property_number}}
                </td>

                <td>
                    {{$booking->property->property_name}}
                </td>

                <td class="text-right">
                    {{$booking->per_day_price}}
                </td>

                <td class="text-center">
                    {{$booking->booking_days}}
                </td>

                <td class="text-right">
                    {{$booking->per_day_price * $booking->booking_days}}
                </td>
                @php
                    $currentRow++;
                @endphp
            </tr>

            

            @if($booking->extra_guest_charge > 0)
                <tr class="item-row">
                    <td class="text-center">
                        {{$booking->property->property_number}}
                    </td>

                    <td>
                        Extra Guest Charge
                    </td>

                    
                    <td class="text-right">
                        {{$booking->property->extra_guest_charge}}
                    </td>

                    <td class="text-center">
                        {{(count($booking->guests) - $booking->property->default_guests) * $booking->booking_days}} 
                    </td>


                    <td class="text-right">
                        {{$booking->extra_guest_charge}}
                    </td>
                </tr>
                @php
                    $currentRow++;
                @endphp
            @endif
            @if($booking->early_checkin_charges > 0)
                <tr class="item-row">
                    <td class="text-center">
                        {{$booking->property->property_number}}
                    </td>

                    <td>
                        Early Check-in Charge
                    </td>

                    
                    <td class="text-right">
                        {{$booking->early_checkin_charges / $booking->booking_days}} 
                    </td>

                    <td class="text-center">
                        {{$booking->booking_days}} 
                    </td>


                    <td class="text-right">
                        {{$booking->early_checkin_charges}}
                    </td>
                </tr>
                @php
                    $currentRow++;
                @endphp
            @endif
            @if($booking->late_checkout_charges > 0)
                <tr class="item-row">
                    <td class="text-center">
                        {{$booking->property->property_number}}
                    </td>

                    <td>
                        Late Check-out Charge
                    </td>

                    
                    <td class="text-right">
                        {{$booking->late_checkout_charges / $booking->booking_days}} 
                    </td>

                    <td class="text-center">
                        {{$booking->booking_days}} 
                    </td>


                    <td class="text-right">
                        {{$booking->late_checkout_charges}}
                    </td>
                </tr>
                @php
                    $currentRow++;
                @endphp
            @endif
            @if($booking->damage_charges > 0)
                <tr class="item-row">
                    <td class="text-center">
                        {{$booking->property->property_number}}
                    </td>

                    <td>
                        Damage Charge
                    </td>

                    
                    <td class="text-right">
                        {{$booking->damage_charges}} 
                    </td>

                    <td class="text-center">
                        1
                    </td>


                    <td class="text-right">
                        {{$booking->damage_charges}}
                    </td>
                </tr>
                @php
                    $currentRow++;
                @endphp
            @endif

            @php
                $remaingRows = $totalRows - $currentRow;
                $margin = 50 * $remaingRows;
            @endphp
            
            @if($currentRow < $totalRows)
                <tr class="item-row" >
                    <td style="padding-bottom:{{$margin}}px"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif

        </tbody>

    </table>

<table class="amount-box">
    <tr>
        <td style="width:55%;">
            <div class="amount-title">
                Amount in Words
            </div>
            <div class="amount-value">
                INR {{numberToWords($booking->total_amount)}} Only
            </div>
        </td>

        <td style="width:45%; padding:0;">
            <table class="summary-table">
                <tr>
                    <td class="summary-label">
                        Total:
                    </td>

                    <td class="summary-value">
                        {{$booking->total_amount}}
                    </td>

                </tr>

                <tr>

                    <td class="summary-label">
                        GST:
                    </td>

                    <td class="summary-value">
                        0.00
                    </td>

                </tr>

                <tr>

                    <td class="summary-label">
                        Grand Total:
                    </td>

                    <td class="summary-value">
                        <strong>{{$booking->total_amount}}</strong>
                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

<table class="invoice-note">

    <tr>
        <td>
            <strong>Note:</strong>
            Regardless of the billing instruction,
            I agree to be held personally liable for
            payment of the total amount of this bill.
        </td>
    </tr>
</table>

<table class="signature-table">

    <tr>

        <td class="left-sign" style="width:50%;">

            <div class="signature-box"></div>

            <div class="signature-label">

                Customer Signature

            </div>

        </td>

        <td class="right-sign" style="width:50%;">

            <div class="signature-box" style="float:right;">

                {{-- Optional Signature Image --}}
                <img src="{{ base_path('assets/frontend/imgs/logo/invoice-signature.png') }}" style="height:45px; width:90% !important; padding:5px;">

            </div>

            <div style="clear:both;"></div>

            <div class="signature-label">

                Partner

            </div>

        </td>

    </tr>

</table>

</body>

</html>
