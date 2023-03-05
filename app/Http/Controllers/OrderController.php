<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function saveOrder(Request $request){
        $validated = Validator::make($request->all(), [
            'customer' => 'required|email'
        ]);

     
        if($validated->fails()){  
            return response()->json(['status' => 'fail','errors' => $validated->errors()]);
        }else{
            $checkCustomer = Customer::where('email', $request->input('customer'))->first();
            if($checkCustomer){
                $data = Order::create(['customer' => $request->input('customer')]);
                return response()->json(['status' => 'success', 'message' => 'Order created successfully', 'data' => $data]);
            }
            return response()->json(['status' => 'success', 'message' => 'Customer email does not exist']);
        }
    }

    public function showOrder(Request $request){
        $validated = Validator::make($request->all(), [
            'id' => 'required|exists:orders',
        ]);

        if($validated->fails()){  
            return response()->json(['status' => 'fail','errors' => $validated->errors()]);
        }else{
            $data = Order::find($request->input('id'))->first();
            return response()->json(['status' => 'success', 'message' => 'Order retrieved successfully', 'data' => $data]);
        }
    }

    public function updateOrder(Request $request){
        $validated = Validator::make($request->all(), [
            'id' => 'required|exists:orders',
            'customer' => 'sometimes|required|email',
        ]);

        if($validated->fails()){  
            return response()->json(['status' => 'fail','errors' => $validated->errors()]);
        }else{
            $data = Order::find($request->input('id'));
            $data->customer = $request->input('customer') !== null ?  $request->input('customer'):$data->customer;
            $data->payed = $request->input('payed') != null ? $request->input('payed'): $data->payed;
            $data->update();
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully', 'data' => $data]);
        }
    }

    public function removeOrder(Request $request){
        $validated = Validator::make($request->all(), [
            'id' => 'required|exists:orders',
        ]);

        if($validated->fails()){  
            return response()->json(['status' => 'fail','errors' => $validated->errors()]);
        }else{
            Order::find($request->input('id'))->delete();
            return response()->json(['status' => 'success', 'message' => 'Order removed successfully']);
        }
    }

    public function addOrderProduct(Request $request, $id){
        $getOrder = Order::find($id);
        if($getOrder){
            $validated = Validator::make($request->all(), [
                'product_id' => 'required',
            ]);
            if($validated->fails()){  
                return response()->json(['status' => 'fail','errors' => $validated->errors()]);
            }else{
                if(empty($getOrder->payed)){
                    $productId = $request->input('product_id');
                    $getProduct = Product::find($productId);
                    if($getProduct){
                        $getOrder->product_id = $productId;
                        $getOrder->update();
                        return response()->json(['status' => 'success', 'message' => 'Product added to Order successfully', 'data' => $getOrder]);
                    }else{
                        return response()->json(['status' => 'success', 'message' => 'Invalid product id']);
                    }

                }else{
                    return response()->json(['status' => 'success', 'message' => 'Product cannot be added',]);
                }                
            }
        }else{
            return response()->json(['status' => 'fail', 'message' => 'OrderID is invalid']);
        }
    }


    public function payOrder(Request $request, $id){
        $getOrder = Order::find($id);
        if(empty($getOrder->payed)){
            $getCustomer = Customer::where('email', $getOrder->customer)->first();
            $getProduct = Product::where('id', $getOrder->product_id)->first();
            $response = Http::post('https://superpay.view.agentur-loop.com/pay', [
                'order_id' => $getOrder->id,
                'customer_email' => $getCustomer->email,
                'value' => $getProduct->price,
            ]);
            if($response->status() === 200){
                $payed= $response->object();
                $getOrder->payed = $payed->message;
                $getOrder->update();

            }
            return response()->json(['status' => 'success', 'message' => $response->object()]);
        }else{
            return response()->json(['status' => 'fail', 'message' => 'OrderID is invalid']);
        }
    }
}
