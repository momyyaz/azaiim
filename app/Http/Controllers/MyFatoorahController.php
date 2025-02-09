<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Model\Cart;
use App\User;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class MyFatoorahController extends Controller {

    public $mfObj;

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * create MyFatoorah object
     */
    public function __construct() {
        $config=\App\CPU\Helpers::get_business_settings('myfatoorah');
//        $this->mfObj = new PaymentMyfatoorahApiV2($config['api_key'], config('myfatoorah.country_iso'), $config['test_mode']);
        $this->mfObj = new PaymentMyfatoorahApiV2(config('myfatoorah.api_key'), config('myfatoorah.country_iso'), config('myfatoorah.test_mode'));
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Create MyFatoorah invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            $paymentMethodId = 0; // 0 for MyFatoorah invoice or 1 for Knet in test mode
            $curlData = $this->getPayLoadData($request);
            // dd($curlData);
                
            $data     = $this->mfObj->getInvoiceURL($curlData, $paymentMethodId);

            $response = ['IsSuccess' => 'true', 'Message' => 'Invoice created successfully.', 'Data' => $data];
            
            return redirect()->to($response['Data']['invoiceURL']);
        } catch (\Exception $e) {
            $response = ['IsSuccess' => 'false', 'Message' => $e->getMessage()];
            
            return response()->json($response);
        }

        // return redirect()->to($response['Data']['invoiceURL']);
        // if ($request->user() == null) {
        //     return redirect()->to($response['Data']['invoiceURL']);
        // } else {

        //     return response()->json($response);
        // }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     *
     * @param int|string $orderId
     * @return array
     */
    private function getPayLoadData($request, $orderId = null) {
        
        $callbackURL = route('myfatoorah.callback');

        $unique_id = OrderManager::gen_unique_id();
        $cart_group_ids = CartManager::get_cart_group_ids();

        foreach ($cart_group_ids as $group_id) {
            $data = [
                'payment_method' => 'myfatoorah',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id
            ];
            OrderManager::generate_order($data);
        }

        $user = auth('customer')->user();
        if ($user == null) {
            $user = $request->user();
        }
        if ($user == null) {
            $user = User::find($request->customer_id);
        }
        
        $value = Order::where('order_group_id', $unique_id)->sum('order_amount');
        
        $phone = $user->phone;
        
        $code = explode(" " , $phone)[0];
        $user_phone = explode(" " , $phone)[1];

        return [
            'CustomerName'       => $user->f_name . ' ' . $user->l_name,
            'InvoiceValue'       => $value,
            'DisplayCurrencyIso' => 'KWD',
            'CustomerEmail'      => $user->email,
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            //'MobileCountryCode'  => '+965',
            'MobileCountryCode'  => $code,
            //'CustomerMobile'     => "$phone",
            'CustomerMobile'     => "$user_phone",
            
            'Language'           => 'en',
            'CustomerReference'  => $unique_id,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ];


    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get MyFatoorah payment information
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function callback() {
        try {
            $paymentId  = request('paymentId');
            $data       = $this->mfObj->getPaymentStatus($paymentId, 'PaymentId');
            if ($data->InvoiceStatus == 'Paid') {
                $status = 'paid';
                CartManager::cart_clean();
            } else if ($data->InvoiceStatus == 'Failed') {
                $status = 'unpaid';
            } else if ($data->InvoiceStatus == 'Expired') {
                $status = 'unpaid';
            }
            Order::where('order_group_id', $data->CustomerReference)->update(['payment_status'=> $status]);



            if ($data->InvoiceStatus == 'Paid') {
                Toastr::success('Payment success.');
                return view('web-views.checkout-complete');
            } else {
                Toastr::error('Payment failed.');
                return redirect()->route('shop-cart');
            }


        } catch (\Exception $e) {
            Toastr::error('Payment failed.');
            return redirect()->route('shop-cart');
            $response = ['IsSuccess' => 'false', 'Message' => $e->getMessage()];
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
