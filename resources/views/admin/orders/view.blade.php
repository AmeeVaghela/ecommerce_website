@extends('layouts.admin')

@section('title','My Orders Details')

@section('content')

<div class="row">
    <div class="col-md-12">

        @if (session('message'))
            <div class="alert alert-success mb-3">{{ session('message') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3> My Orders Details </h3>
            </div>
            <div class="card-body">

                    <div class="shadow bg-white p-3">

                        <h4 class="text-primary">
                            <i class="fa fa-shopping-cart text-dark"></i> My Order Details
                            <a href="{{ url('admin/orders')}}" class="bt btn-danger btn-sm float-end mx-1">Back</a>
                            <a href="{{ url('admin/invoice/'.$order->id.'/generate') }}" class="bt btn-primary btn-sm
                                mx-1">Download Invoice</a>
                            <a href="{{ url('admin/invoice/'.$order->id) }}" target="_blank" class="bt btn-warning btn-sm
                                 mx-1">View Invoice</a>
                        </h4>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Order Details</h5>
                                <hr>
                                <h6>Order Id : {{$order->id}} </h6>
                                <h6>Tracking Id/No : {{$order->tracking_no}}</h6>
                                <h6>Order Created Date : {{ $order->created_at->format('d-m-Y h:i A') }}</h6>
                                <h6>Payment Mode : {{ $order->payment_mode}}</h6>
                                <h6  class="border p-2 text-success">
                                    Order Status Message : <span class="text-uppercase">{{$order->status_message}}</span>
                                </h6>

                            </div>
                            <div class="col-md-6">
                                <h5>User Details</h5>
                                <hr>
                                <h6>Full Name : {{$order->fullname}} </h6>
                                <h6>Email Id : {{$order->email}} </h6>
                                <h6>Phone : {{$order->phone}} </h6>
                                <h6>Pincode : {{$order->pincode}} </h6>
                                <h6>Address : {{$order->address}} </h6>

                            </div>
                        </div>

                        <br/>
                        <h5>Order Items</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table=bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Item ID</th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalPrice = 0;
                                    @endphp
                                   @foreach ($order->orderItems as $orderItem)
                                    <tr>
                                        <td width="10%">{{ $orderItem->id }}</td>
                                        <td width="10%">

                                            {{-- @if ($orderItem->product->productImage)
                                                <img src="{{ asset($orderItem->product->productImage[0]->image) }}"
                                                style="width: 50px; height: 50px"
                                                alt="{{ $orderItem->product->name }}">
                                            @else
                                                <img src="" style="width: 50px; height: 50px"
                                                alt="{{ $orderItem->product->name }}">
                                            @endif --}}

                                        </td>
                                        <td>
                                            {{-- {{ $orderItem->product->name }}
                                            @if ($orderItem->productColor)
                                                @if ( $orderItem->productColor->color)
                                                <span>- Color : {{ $orderItem->productColor->color->name }}</span>
                                                @endif
                                            @endif --}}
                                        </td>
                                        <td width="10%"> ${{ $orderItem->price }}</td>
                                        <td width="10%">{{ $orderItem->quantity }}</td>
                                        <td width="10%" class="fw-bold">$ {{ $orderItem->quantity * $orderItem->price }}</td>
                                        @php
                                            $totalPrice += $orderItem->quantity * $orderItem->price;
                                        @endphp
                                    </tr>
                                   @endforeach
                                   <tr>
                                    <td colspan="5" class="fw-bold"> Total Amount : </td>
                                    <td colspan="1" class="fw-bold">$ {{ $totalPrice }}</td>
                                   </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border mt-3">
            <div class="card-body">
                <h4>Order Process (Order Status Updates) </h4>
                <hr>
                <div class="row">
                    <div class="col-md-5">
                        <form action="{{ url('/admin/orders/'.$order->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <label>Update your order status</label>
                        <div class="input-group">
                            <select name="order_status" class="form-select">
                                <option value="">Select Status</option>
                                <option value="in progess" {{ Request:: get('status') == 'in progess' ? 'selected':'' }}>In progress</option>
                                <option value="completed" {{ Request:: get('status') == 'completed' ? 'selected':'' }}>Completed</option>
                                <option value="pending" {{ Request:: get('status') == 'pending' ? 'selected':'' }}>Pending</option>
                                <option value="out-for-delivery" {{ Request:: get('status') == 'out-for-delivery' ? 'selected':'' }}>Out for Delivery</option>
                                <option value="cancelled" {{ Request:: get('status') == 'cancelled' ? 'selected':'' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary text-white">Update </button>
                        </div>

                        </form>
                    </div>

                    <div class="col-md-7">
                        <br/>
                        <h4 class="mt-3">Current Order Status: <span class="text-uppercase">{{ $order->status_message}}</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
