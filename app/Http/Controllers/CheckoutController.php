<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Cart;
use App\Transaction;
use App\TransactionDetail;

use Exception;

use Midtrans\Config;
use Midtrans\Snap;  


class CheckoutController extends Controller
{
    public function process(Request $request) {
        // save user data
        $user = Auth::user();
        $user->update($request->except('total_price'));

        // Proses Checkout
        $code = 'STORE-' . mt_rand(00000,99999);
        $carts = Cart::with(['product', 'user'])->where('users_id', Auth::user()->id)->get();

        // transaction create
        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'insurance_price' => 0,
            'shipping_price' => 0,
            'total_price' => $request->total_price,
            'transaction_status' => 'PENDING',
            'code' => $code
        ]);

        foreach ($carts as $cart) {
            $trx = 'TRX-' . mt_rand(00000,99999);

            TransactionDetail::create([
            'transactions_id' => $transaction->id,
            'products_id' => $cart->product->id,
            'price' => $cart->product->price,
            'shipping_status' => 'PENDING',
            'resi' => '',
            'code' => $trx
            ]);
        }
        
        // Delete cart data
        Cart::where('users_id', Auth::user()->id)->delete();

        //konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.id3ds');

        // buat array untuk midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $code,
                'gross_amount' => (int) $request->total_price
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payment' => [
                'gopay', 'permata_va' ,'indomaret', 'gopay', 'bank_transfer'
            ],
            'vtweb' => []
        ];

        try {
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            return redirect($paymentUrl);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }

    }
}
