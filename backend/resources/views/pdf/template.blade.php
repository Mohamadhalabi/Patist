<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quote #{{$order}}</title>

    <style>
        .infoo {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #6E626D;
        }
        .infoo th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #6E626D;
        }
        .my-table {
            margin: 0 auto;
            border-collapse: collapse;
            border: 1px solid #6E626D!important;
            color: #6E626D;

        }
        .my-cell {
            border: 1px solid black;
            padding: 10px;
        }
        p{
            margin-bottom: -20px;

        }
    </style>
</head>
<body>
@include('pdf')

<div>
    <img src="https://patent.istanbul/images/logo2.png" width="170" height="90" alt="@lang('pdf.logo_alt_text')">
</div>
<h2 style="color:#3a4f76;float: right;margin-top:-60px;margin-right: 100px;font-size: 30px;font-weight: normal">@lang('pdf.quote_title')</h2>
<h2 style="color: #3a4f76;font-size: 20px;font-weight: normal;margin-top: 30px">@lang('pdf.patent_istanbul')</h2>
<p style="font-family: DejaVu Sans;font-size: 12px;line-height: 16px;color: #6E626D">
    @lang('pdf.address')<br>
    @lang('pdf.phone')
</p>
<table class="infoo" style="width: 25%;float: right;text-align: center;font-size: 14px;margin-top: -80px;margin-right: 40px">
    <tr style="text-align: center">
        <td style="font-weight: bold;">@lang('pdf.date')</td>
        <td>{{ date('d-m-Y') }}</td>
    </tr>
    <tr style="text-align: center">
        <td style="font-weight: bold">@lang('pdf.quote_no')</td>
        <td>#{{ $order }}</td>
    </tr>
</table>

<div class="header">
    <h2 style="color: #3a4f76;font-size: 20px;font-weight: normal;margin-top: 30px">@lang('pdf.quote_for')</h2>
</div>
<div class="header" style="color:#6E626D">
    @lang('pdf.name'): {{ $orderdetails['Name'] }}<br>
    @lang('pdf.phone'): {{ $orderdetails['Phone'] }}<br>
    @lang('pdf.email'): {{ $orderdetails['Email'] }}<br>
</div>
<br><br>
<table class="my-table" style="border: 1px solid #6E626D;font-size: 15px;margin-left: auto;margin-right: auto;">
    <tr>
        <td class="my-cell" style="width: 100px;text-align: center;border: 1px solid #6E626D">@lang('pdf.case')</td>
        <td class="">
            <p>{{ $orderdetails['service'] }}</p><br>
            <p>@lang('pdf.application_number'): {{ $orderdetails['Application Number'] }}</p><br>
            <p>@lang('pdf.title'): {{ $orderdetails['Title'] }}</p><br>
            <p>@lang('pdf.publication_date'): {{ $orderdetails['Publication Date'] }}</p><br>
            <p>@lang('pdf.your_reference'): {{ $orderdetails['Your Reference'] }}</p><br>
        </td>
    </tr>
    @if($orderdetails['service'] == "EP Validation")
        <tr>
            <td class="my-cell" style="width: 100px;text-align: center;border: 1px solid #6E626D">@lang('pdf.quote')</td>
            <td class="">
                <p>@lang('pdf.official_fees'): {{ $orderdetails['Official fees'] }} €</p><br>
                <p style="padding-left: 20px">@lang('pdf.ep_validation_official_fee'): {{ $orderdetails['EP validation official fee'] }} TL (EUR/TRY={{ $eurocurrency }})</p><br>

                @if($orderdetails['Extension of 3 months official fees'] != null)
                    <p style="padding-left: 20px">@lang('pdf.extension_3_months_fees'): {{ $orderdetails['Extension of 3 months official fees'] }} TL</p><br>
                @endif
                <p>@lang('pdf.service_fees'): {{ $orderdetails['Service Fees'] }} €</p><br>
                <p style="padding-left: 20px">@lang('pdf.ep_validation_service_fees'): {{ $orderdetails['EP validation service fees'] }} €</p><br>

                @if($orderdetails['late_service_fee'] != null)
                    <p style="padding-left: 20px">@lang('pdf.ep_validation_additional_time_fee'): {{ $orderdetails['late_service_fee'] }} €</p><br>
                @endif
                <p>@lang('pdf.translation_fee'): {{ $orderdetails['Translation Fees'] }} €</p><br>
                <p style="padding-left: 20px;">@lang('pdf.estimated_words'): {{ $orderdetails['Estimated words in claims, description, and figures'] }}</p><br>
                <p style="padding-left: 20px">@lang('pdf.translation_fees'): {{ $orderdetails['Translation Fees'] }} ({{ $orderdetails['Translation fees per word'] }} €/word)</p><br>
                <p>@lang('pdf.total_payable'): {{ $orderdetails['Total Payable'] }} €</p><br>
            </td>
        </tr>
    @elseif($orderdetails['service'] == "PCT Entry")
        <tr>
            <td class="my-cell" style="width: 100px;text-align: center;border: 1px solid #6E626D">@lang('pdf.quote')</td>
            <td class="">
                <p>@lang('pdf.official_fees'): {{ $orderdetails['Official fees'] }} $</p><br>
                <p style="padding-left: 20px">@lang('pdf.renewal_fee'): {{ $orderdetails['renewal_fee'] }} TL (USD/TRY={{ $usdcurrency }})</p><br>
                <p style="padding-left: 20px">@lang('pdf.pct_entry_fee'): {{ $orderdetails['pct_entry_fee'] }} TL</p><br>
                <p style="padding-left: 20px">@lang('pdf.examination_fee'): {{ $orderdetails['examination_fee'] }} TL</p><br>
                <p style="padding-left: 20px">@lang('pdf.priority_fee'): {{ $orderdetails['priority_length'] }} x {{ $orderdetails['priority_fee'] }}: {{ $orderdetails['examination_fee'] }} TL</p><br>

                @if($orderdetails['late_official_fee'] > 0)
                    <p style="padding-left: 20px">@lang('pdf.late_official_fees'): {{ $orderdetails['late_official_fee'] }} TL</p><br>
                @endif
                <p>@lang('pdf.service_fees'): {{ $orderdetails['Service Fees'] }} $</p><br>
                <p style="padding-left: 20px">@lang('pdf.pct_entry_service_fees'): {{ $orderdetails['pct_entry_service_fees'] }} $</p><br>
                <p>@lang('pdf.total_payable'): {{ $orderdetails['Total Payable'] }} $</p><br>
            </td>
        </tr>
    @endif
</table>
</body>
</html>
