<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order Invoice {{date('F d Y')}}</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.totalFnf td {
            background: #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
            }

            /* ... the rest of the rules ... */
            div.divFooter {
                position: fixed;
                bottom: 0;
                width: calc(100% - 60px);
                text-align: center;
            }

            .invoice-box table tr.heading td {
                background: #eee !important;

            }
        }

        @media screen {
            div.divFooter {
                display: none;
            }
        }
    </style>
    <script>
        window.onload = function (){
            window.print()
        }
    </script>
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="4">
                <table>
                    <tr>
                        <td class="title">

                            <img src="{{url($company_info['company']['logo'])}}" style="width:100%; max-width:300px;">
                        </td>
                        <td>
                            {{ucfirst($company_info['company']['name'])}}<br>
                            {{ucfirst($company_info['name'])}}<br>
                            {{ucfirst($company_info['company']['email'])}}<br>
                            {{ucfirst($company_info['company']['phone'])}}<br>
                            NTN : {{ucfirst($company_info['company']['ntn'])}}
                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="4">
                <table>
                    <tr>
                        <td>
                            {{$data['supplier']['name']}}<br>
                            Email: {{$data['supplier']['email']}}<br>
                            Tel No: {{$data['supplier']['phone']}}<br>
                            Address: {{$data['supplier']['address']}}<br>
                        </td>

                        <td>
                            Invoice #: {{$data['id']}}<br>
                            Invoice Date: {{date('F d Y',strtotime($data['order_date']))}}<br>
                            Issue Date: {{date('F d Y')}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

<!--        <tr class="heading">
            <td>
                Payment Method
            </td>

            <td>
                Check #
            </td>
        </tr>

        <tr class="details">
            <td>
                Check
            </td>

            <td>
                1000
            </td>
        </tr>-->

        <tr class="heading">
            <td>
                Item
            </td>

            <td  width="20%">
                Unit Price
            </td>
            <td  width="20%" style="text-align: right">
                Quantity
            </td>
            <td width="20%" style="text-align: right">
                Total
            </td>
        </tr>
        @foreach($orders as $order)
            <tr>
                <td >{{$order['item']['name']}}</td>
                <td>@price($order['unit_cost'])</td>
                <td style="text-align: right">{{$order['quantity']}}</td>
                <td style="text-align: right">@price($order['total'])</td>
            </tr>
        @endforeach


        <tr class="totalFnf">
            <td colspan="3">Total</td>


            <td>
                @price($data['grand_total'])
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top: 20px">
                <strong>Note</strong><br>
                <p style="margin-top: 5px">{{ucfirst($company_info['company']['note'])}}
                </p>
            </td>
        </tr>
    </table>
    <div class="divFooter" style="text-align: center">
        <p class="center-text">Invoice Generated by PocketSystems</p>
    </div>
</div>
<script>
    setTimeout(function (){
        window.close()
    },1000)
</script>
</body>
</html>


