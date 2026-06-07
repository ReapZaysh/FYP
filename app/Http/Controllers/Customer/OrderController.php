<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }
        $tableNumber = session('table_number', 'Counter');

        $vouchers = collect([]);
        if (auth()->check()) {
            $vouchers = $this->firebase->getVouchers(auth()->id())->filter(function($v) {
                return !($v['is_used'] ?? false);
            });
        }

        return view('customer.cart', compact('cart', 'total', 'tableNumber', 'vouchers'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = $this->firebase->getProduct($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found!');
        }

        $quantity = $request->input('quantity', 1);
        if ($quantity < 1)
            $quantity = 1;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product['name'],
                "quantity" => $quantity,
                "price" => $product['price'],
                "category_id" => $product['category_id'] ?? null,
                "image" => $product['image_path'] ?? ''
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $totalCount = array_sum(array_column($cart, 'quantity'));
            return response()->json([
                'success' => true,
                'message' => $product['name'] . ' added to cart!',
                'cart_count' => $totalCount
            ]);
        }

        return redirect()->back()->with('success', $product['name'] . ' added to cart!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
                $msg = "Quantity decreased.";
            } else {
                unset($cart[$id]);
                $msg = "Item removed from cart.";
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', $msg ?? 'Cart updated.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $orderReference = strtoupper(Str::random(8));
        $tableNumber = session('table_number', 'Counter');

        $items = [];
        foreach ($cart as $id => $details) {
            $items[] = [
                'product_id' => $id,
                'category_id' => $details['category_id'] ?? null,
                'name' => $details['name'],
                'quantity' => $details['quantity'],
                'price' => (float) $details['price'],
                'image' => $details['image'] ?? null,
                'note' => $request->input("notes.$id")
            ];

            // Update product order count for trending (in Firebase)
            $product = $this->firebase->getProduct($id);
            if ($product) {
                $product['order_count'] = ($product['order_count'] ?? 0) + $details['quantity'];
                $this->firebase->saveProduct($id, $product);
            }
        }

        $orderNote = $request->input('order_note');
        
        // Handle Voucher application
        $voucherId = $request->input('voucher_id');
        if (auth()->check() && $voucherId) {
            $userId = auth()->id();
            $vouchers = $this->firebase->getVouchers($userId);
            $appliedVoucher = $vouchers->firstWhere('id', $voucherId);
            
            if ($appliedVoucher && !($appliedVoucher['is_used'] ?? false)) {
                // Mark voucher as used
                $this->firebase->markVoucherAsUsed($userId, $voucherId);
                
                // Append to order note for staff to see
                $rewardName = $appliedVoucher['reward_name'] ?? 'Reward';
                $voucherNote = "[VOUCHER APPLIED: " . strtoupper($rewardName) . "]";
                $orderNote = $orderNote ? $voucherNote . "\n" . $orderNote : $voucherNote;
            }
        }

        $orderData = [
            'reference' => $orderReference,
            'table_number' => $tableNumber,
            'customer_name' => $request->filled('customer_name') ? $request->input('customer_name') : 'Guest',
            'customer_id' => auth()->check() ? auth()->id() : null,
            'order_note' => $orderNote,
            'total_amount' => (float) $total,
            'status' => 'submitted',
            'items' => $items,
            'created_at' => now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
        ];

        $this->firebase->syncOrder($orderData);

        session()->forget('cart');

        return redirect()->route('customer.track', $orderReference);
    }

    public function track($reference)
    {
        $order = $this->firebase->getOrder($reference);

        if (!$order) {
            abort(404);
        }

        return view('customer.track', compact('order'));
    }
}
