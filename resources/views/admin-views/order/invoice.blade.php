<html lang="ar" dir="rtl">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{\App\CPU\translate('invoice')}}</title>
	<meta http-equiv="Content-Type" content="text/html;"/>
	<meta charset="UTF-8">
	<style media="all">
        /* IE 6 */
        * html .footer {
            position: absolute;
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
        }

        * {
            line-height: 1.3;
            font-family: sans-serif;
            color: #333542;
            direction: rtl;
        }

        body {
            font-size: .75rem;
            width: 80mm;
            margin: auto;
        }

        img {
            max-width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            padding: 2px;
            font-size: 11px;
            text-align: right;
        }

        table tbody th,
        table tbody td {
            padding: 2px;
            font-size: 11px;
            text-align: right;
        }

        .content-position {
            padding: 10px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-1 {
            margin-bottom: 4px !important;
        }

        .mb-2 {
            margin-bottom: 8px !important;
        }

        .mb-4 {
            margin-bottom: 24px !important;
        }

        .mb-30 {
            margin-bottom: 30px !important;
        }

        .px-10 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .fz-14 {
            font-size: 14px;
        }

        .fz-12 {
            font-size: 12px;
        }

        .fz-10 {
            font-size: 10px;
        }

        .font-weight-bold {
            font-weight: 700;
        }

        .border-dashed-top {
            border-top: 1px dashed #ddd;
        }

        .bg-light {
            background-color: #F7F7F7;
        }

        .py-4 {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .d-flex {
            display: flex;
        }

        .gap-2 {
            gap: 8px;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-center {
            justify-content: center;
        }

        table.customers thead th {
            background-color: #0177CD;
            color: #fff;
        }

        table.customers tbody th,
        table.customers tbody td {
            background-color: #FAFCFF;
        }

        .h2 {
            font-size: 1.5em;
            font-weight: bold;
        }

        .h4 {
            font-weight: bold;
        }

        @media print {
            @page {
                size: 8cm 29.7cm;
                margin: 0;
                padding: 4px;
                width: 8cm;
                height: 29.7cm;
            }
            print-quality: high;
            -webkit-print-quality: high;
        }

	</style>
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body style="padding: 4px;width: 302.4px !important;">
<div>
	<table class="content-position mb-5">
		<tr>
			<th class="p-0 text-right" style="font-size: 20px">
				رقم الفاتورة #{{ $order->id }}
			</th>
			<th>
				<img height="40" src="{{asset("storage/app/public/company/$company_web_logo")}}" alt="">
			</th>
		</tr>
	</table>

	<table class="bs-0 px-10">
		<tr>
			<th class="content-position-y text-right">
				<h4 class="fz-12">{{\App\CPU\translate('date')}} : {{Carbon\Carbon::parse($order['created_at'])->translatedFormat('Y-m-d h:i A')}}</h4>
				<h4 class="text-uppercase mb-1 fz-12">
					اسم المتجر
					: {{ $order->seller_is == 'admin' ? $company_name : (isset($order->seller->shop) ? $order->seller->shop->name : \App\CPU\translate('not_found')) }}
				</h4>
				@if($order['seller_is']!='admin' && isset($order['seller']) && $order['seller']->gst != null)
					<h4 class="text-capitalize fz-12">{{\App\CPU\translate('GST')}}
						: {{ $order['seller']->gst }}</h4>
				@endif
			</th>
		</tr>
	</table>
</div>

<br>

<div>
	<section class="d-flex justify-content-between">
		@if ($order->shippingAddress)
			<div>
				<span class="h3" style="font-weight: bold;margin: 0px;">{{\App\CPU\translate('shipping_to')}} </span>
				<div class="h5">
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['f_name'].' '.$order->customer['l_name']:\App\CPU\translate('name_not_found')}}</p>
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['email']:\App\CPU\translate('email_not_found')}}</p>
					<p style=" margin-top: 6px; margin-bottom:0px;direction: ltr;text-align: right">{{$order->customer !=null? $order->customer['phone']:\App\CPU\translate('phone_not_found')}}</p>
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->shippingAddress ? $order->shippingAddress['address'] : ""}}</p>
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->shippingAddress ? $order->shippingAddress['city'] : ""}} {{$order->shippingAddress ? $order->shippingAddress['zip'] : ""}}</p>
				</div>
			</div>

		@else
			<div>
				<span class="h3" style="font-weight: bold;margin: 0px;">{{\App\CPU\translate('customer_info')}} </span>
				<div class="h5">
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['f_name'].' '.$order->customer['l_name']:\App\CPU\translate('name_not_found')}}</p>
					@if (isset($order->customer) && $order->customer['id']!=0)
						<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['email']:\App\CPU\translate('email_not_found')}}</p>
						<p style=" margin-top: 6px; margin-bottom:0px;direction: ltr;text-align: right">{{$order->customer !=null? $order->customer['phone']:\App\CPU\translate('phone_not_found')}}</p>
					@endif
				</div>
			</div>
		@endif

		@if ($order->billingAddress)
			<div style="margin-right: 10px">
				<span class="h3" style="font-weight: bold;margin: 0px;">{{\App\CPU\translate('billing_address')}} </span>
				<div class="h5">
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['contact_person_name'] : ""}}</p>
					<p style=" margin-top: 6px; margin-bottom:0px;direction: ltr;text-align: right">{{$order->billingAddress ? $order->billingAddress['phone'] : ""}}</p>
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['address'] : ""}}</p>
					<p style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['city'] : ""}} {{$order->billingAddress ? $order->billingAddress['zip'] : ""}}</p>
				</div>
			</div>
		@endif
	</section>
</div>

<br>

<div>
	<div class="content-position-y">
		<table class="customers bs-0">
			<thead>
			<tr>
				<th>{{\App\CPU\translate('SL')}}</th>
				<th>{{\App\CPU\translate('item_description')}}</th>
				<th>
					{{\App\CPU\translate('unit_price')}}
				</th>
				<th>
					{{\App\CPU\translate('qty')}}
				</th>
				<th class="text-right">
					{{\App\CPU\translate('total')}}
				</th>
			</tr>
			</thead>
			@php
				$subtotal=0;
				$total=0;
				$sub_total=0;
				$total_tax=0;
				$total_shipping_cost=0;
				$total_discount_on_product=0;
				$ext_discount=0;
			@endphp
			<tbody>
			@foreach($order->details as $key=>$details)
				@php $subtotal=($details['price'])*$details->qty @endphp
				<tr>
					<td>{{$key+1}}</td>
					<td style="max-width: 5px !important;">
						{{$details['product']?$details['product']->name:''}}
						@if($details['variant'])
							<br>
							{{\App\CPU\translate('variation')}} : {{$details['variant']}}
						@endif
					</td>
					<td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($details['price']))}}</td>
					<td>{{$details->qty}}</td>
					<td class="text-right">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
				</tr>

				@php
					$sub_total+=$details['price']*$details['qty'];
					$total_tax+=$details['tax'];
					$total_shipping_cost+=$details->shipping ? $details->shipping->cost :0;
					$total_discount_on_product+=$details['discount'];
					$total+=$subtotal;
				@endphp
			@endforeach
			</tbody>
		</table>
	</div>
</div>
<?php
if ($order['extra_discount_type'] == 'percent') {
	$ext_discount = ($sub_total / 100) * $order['extra_discount'];
} else {
	$ext_discount = $order['extra_discount'];
}
?>
@php($shipping=$order['shipping_cost'])
<div class="content-position-y">
	<table class="fz-12">
		<tr>
			<th class="text-right">
				<h4 class="fz-12 mb-1">{{\App\CPU\translate('payment_details')}}</h4>
				<p class="fz-12 font-normal">{{$order->payment_status}}, {{date('y-m-d',strtotime($order['created_at']))}}</p>
				@if ($order->delivery_type != null)
					<h4 class="mb-1">{{\App\CPU\translate('delivery_info')}} </h4>
					@if ($order->delivery_type == 'self_delivery')
						<p class="font-normal">
                            <span>
                                {{\App\CPU\translate('self_delivery')}}
                            </span>
							<br>
							<span>
                                {{\App\CPU\translate('delivery_man_name')}} : {{$order->delivery_man['f_name'].' '.$order->delivery_man['l_name']}}
                            </span>
							<br>
							<span>
                                {{\App\CPU\translate('delivery_man_phone')}} : <span style="direction: ltr !important;">{{$order->delivery_man['phone']}}</span>
                            </span>
						</p>
					@else
						<p>
                        <span>
                            {{$order->delivery_service_name}}
                        </span>
							<br>
							<span>
                            {{\App\CPU\translate('tracking_id')}} : {{$order->third_party_delivery_tracking_id}}
                        </span>
						</p>
					@endif
				@endif

			</th>
			<th>
				<table class="calc-table">
					<tbody>
					<tr>
						<td class="p-1 text-right">{{\App\CPU\translate('sub_total')}}</td>
						<td class="p-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($sub_total))}}</td>
					</tr>
					<tr>
						<td class="p-1 text-right">{{\App\CPU\translate('tax')}}</td>
						<td class="p-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax))}}</td>
					</tr>
					@if ($order->order_type=='default_type')
						<tr>
							<td class="p-1 text-right">{{\App\CPU\translate('shipping')}}</td>
							<td class="p-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</td>
						</tr>
					@endif
					<tr>
						<td class="p-1 text-right">{{\App\CPU\translate('coupon_discount')}}</td>
						<td class="p-1">
							- {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->discount_amount))}} </td>
					</tr>
					@if ($order->order_type=='POS')
						<tr>
							<td class="p-1 text-right">{{\App\CPU\translate('extra_discount')}}</td>
							<td class="p-1">
								- {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($ext_discount))}} </td>
						</tr>
					@endif
					<tr>
						<td class="p-1 text-right">{{\App\CPU\translate('discount_on_product')}}</td>
						<td class="p-1">
							- {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_discount_on_product))}} </td>
					</tr>
					<tr>
						<td class="border-dashed-top font-weight-bold text-right"><b>{{\App\CPU\translate('total')}}</b></td>
						<td class="border-dashed-top font-weight-bold">
							{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}
						</td>
					</tr>
					</tbody>
				</table>
			</th>
		</tr>
	</table>
</div>
<hr>
<div class="row">
	<section>
		<table>
			<tr>
				<th class="content-position-y bg-light py-2">
					<div class="d-flex justify-content-between gap-2">
						<div class="mb-2">
							{{\App\CPU\translate('phone')}}
							: <span style="direction: ltr !important;">{{ $company_phone }}</span>
						</div>
						<div class="mb-2">
							البريد: {{$company_email}}
						</div>
					</div>
					<div class="mb-2">
						{{\App\CPU\translate('website')}}
						: <a href="{{ url("/") }}" target="_blank">{{ url("/") }}</a>
					</div>
					<div>
						<small style="margin-bottom: 20px;">إذا كنت بحاجة إلى أي مساعدة أو لديك ملاحظات أو اقتراحات حول موقعنا، يمكنك مراسلتنا عبر البريد الإلكتروني على
							<a href="mail::to({{ $company_email }})">{{ $company_email }}</a>
						</small>
						<p class="fz-10 text-center">
							<span>جميع الحقوق محفوظة</span>
							<span>© {{date('Y')}} {{$company_name}}</span>
						</p>
					</div>
				</th>
			</tr>
		</table>
	</section>
</div>
</body>
</html>
<script>
    window.print();
</script>
