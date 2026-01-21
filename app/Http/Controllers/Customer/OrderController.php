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
        return view('customer.cart', compact('cart', 'total'));
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
                "image" => $product['image_path'] ?? ''
            ];
        }

        session()->put('cart', $cart);
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

        $items = [];
        foreach ($cart as $id => $details) {
            $items[] = [
                'product_id' => $id,
                'name' => $details['name'],
                'quantity' => $details['quantity'],
                'price' => (float) $details['price'],
                'note' => $request->input("notes.$id")
            ];

            // Update product order count for trending (in Firebase)
            $product = $this->firebase->getProduct($id);
            if ($product) {
                $product['order_count'] = ($product['order_count'] ?? 0) + $details['quantity'];
                $this->firebase->saveProduct($id, $product);
            }
        }

        $orderData = [
            'reference' => $orderReference,
            'customer_name' => $request->input('customer_name', 'Guest'),
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
