@extends('layouts.app')
@section('title')
    Selling Cart
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        {{-- <div class="card-header nav-tabs">
            <a style="width: 49.5%" class=" btn-block btn bt-primary" id="tab1-button"><b
                    style="font-size:18px">Wholesaling</b></a>
            <a style="width: 49.5%" class=" btn-block btn bt-primary" id="tab2-button"><b
                    style="font-size:18px">Retailing</b></a>
        </div> --}}
        <div class="card-body">
            <div class="tabs-container">
                <div class="tab-content" id="tab1-content" style="display: block;">
                    @include('includes.wholesale')
                </div>
                {{-- <div class="tab-content" id="tab2-content" style="display: none;">
                    @include('includes.retail')
                </div> --}}
            </div>
        </div>
    </div>
    <br>
    <div class="my-2 card">
        <div class="card-header">
            <div class="row">
                <div class="col text-center">
                    <h5 class="my-0">
                        <b>{{ __('RECENT SALES') }}
                        </b>
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @php
                $totalAmount = 0;
            @endphp
            <table id="data-tebo1"
                class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="rounded-3 shadow ">
                    <th style="max-width: 20px">#</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th class="text-right">Price</th>
                    <th>Date</th>
                    <th>Seller</th>
                </thead>
                <tbody>
                    @foreach ($sales as $index => $sale)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $sale->name . ' - ' . $sale->volume . ' ' . $sale->measure }}</td>
                            <td>{{ $sale->quantity . ' ' . $sale->unit }}</td>
                            <td class="text-right">{{ number_format($sale->price, 0, '.', ',') }}
                                Tsh</td>
                            @php
                                $totalAmount += $sale->price;
                            @endphp
                            <td class="">{{ $sale->created_at->format('D, d M Y \a\t H:i:s') }} </td>
                            <td>{{ $sale->seller }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="font-size: 18px;font-weight:bold">Total</td>
                    <td style="font-size: 18px;font-weight:bold" class="text-right">
                        {{ number_format($totalAmount, 0, '.', ',') }} Tsh</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
        jQuery(document).ready(function() {
            $("#cartCollapse").collapse("show");
        });
        jQuery(document).ready(function() {
            checkCart();

            function checkCart() {
                $.ajax({
                    url: 'check-cart',
                    method: 'get',
                    success: function(data) {
                        if (data.cartData) {
                            renderTable();
                        } else {
                            console.log('heree');
                            $('#cart-container').html("<p>Your cart is empty</p>");
                        }
                    }
                });
            }


            function renderTable() {
                $.ajax({
                    url: 'get-cart-data',
                    method: 'get',
                    success: function(cartData) {
                        var newTable =
                            '<table class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover" style="width: 100%">' +
                            '<thead class="rounded-3 shadow ">' +
                            '<th>Product</th>' +
                            '<th class="">Price</th>' +
                            '<th>Quantity</th>' +
                            '<th class="text-right">Subtotal</th>' +
                            '<th></th>' +
                            '</thead>' +
                            '<tbody>';
                        var total = 0;
                        $.each(cartData.cart, function(index,
                            value) {
                            newTable += '<tr data-id="' +
                                value.id + '">' +
                                '<td>' + value.name +
                                '<div>' + value.volume +
                                ' ' + value.measure +
                                '</div></td>' +
                                '<td class="price">' + value
                                .price.toLocaleString() +
                                ' Tsh</td>' +
                                '<td><input style="width: 80px" type="number" min="1" value="' +
                                value.quantity +
                                '" class="form-control quantity update-cart" /></td>' +
                                '<td class="text-right subtotal">' +
                                (value.price * value
                                    .quantity)
                                .toLocaleString() +
                                ' Tsh</td>' +
                                '<td><button class="btn btn-outline-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button></td>' +
                                '</tr>';
                            total += value.price * value
                                .quantity;
                        });
                        newTable += '</tbody>' +
                            '<tfoot>' +
                            '<tr>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td><h5>Total</h5></td>' +
                            '<td class="text-right">' +
                            '<h5><strong class="total">' + total
                            .toLocaleString() +
                            ' Tsh</strong></h5>' +
                            '</td>' +
                            '</tr>' + '<tr class="">' +
                            '<tr class="select-customer">' +
                            '<td colspan="2"></td>' +
                            '<td  class="text-center">' +
                            '<select class="form-control customer-select">' +
                            '<option value=""> -- Select Customer -- </option>';
                        $.each(cartData.customers, function(index, value) {
                            newTable += '<option value="' + value.id + '">' + value.name +
                                '</option>';
                        });
                        newTable += '</select>' +
                            '</td>' +
                            '<td></td>' +
                            '</tr>' +
                            '<tr class="">' +
                            '<td colspan="5" class="text-center ">' +
                            '<a href="{{ route('empty.cart') }}" class="btn btn-danger btn-sm my-2 mx-2"><i class=""></i>Empty Cart</a>' +
                            '<button class="btn btn-sm my-2 mx-2 btn-success checkout-btn">RECORD PAID SALE</button>' +
                            '<button class="btn btn-sm my-2 mx-2 btn-warning unpaid-btn">SAVE UNPAID SALE</button>' +
                            '</td>' +
                            '</tr>';
                        '</tfoot>' +
                        '</table>';
                        $('#cart-container').html(newTable);
                    }
                });
            };
        });
    </script>
    <script>
        jQuery(document).ready(function() {

            $('.add-to-cart-form').click(function(event) {
                event.preventDefault();
                var ele = $(this);
                var id = ele.attr("data-id")
                $.ajax({
                    url: 'add-to-cart/' + id,
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            $('.cart-dropdown .btn .badge .btn').text(data.count);
                            $('.cart-count').text(data.count + ' Products');
                            $.ajax({
                                url: 'get-cart-data',
                                method: 'get',
                                success: function(cartData) {
                                    var newTable =
                                        '<table class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover" style="width: 100%">' +
                                        '<thead class="rounded-3 shadow ">' +
                                        '<th>Product</th>' +
                                        '<th class="">Price</th>' +
                                        '<th>Quantity</th>' +
                                        '<th class="text-right">Subtotal</th>' +
                                        '<th></th>' +
                                        '</thead>' +
                                        '<tbody>';
                                    var total = 0;
                                    $.each(cartData.cart, function(index,
                                        value) {
                                        newTable += '<tr data-id="' +
                                            value.id + '">' +
                                            '<td>' + value.name +
                                            '<div>' + value.volume +
                                            ' ' + value.measure +
                                            '</div></td>' +
                                            '<td class="price">' + value
                                            .price.toLocaleString() +
                                            ' Tsh</td>' +
                                            '<td><input style="width: 80px" type="number" min="1" value="' +
                                            value.quantity +
                                            '" class="form-control quantity update-cart" /></td>' +
                                            '<td class="text-right subtotal">' +
                                            (value.price * value
                                                .quantity)
                                            .toLocaleString() +
                                            ' Tsh</td>' +
                                            '<td><button class="btn btn-outline-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button></td>' +
                                            '</tr>';
                                        total += value.price * value
                                            .quantity;
                                    });
                                    newTable += '</tbody>' +
                                        '<tfoot>' +
                                        '<tr>' +
                                        '<td></td>' +
                                        '<td></td>' +
                                        '<td><h5>Total</h5></td>' +
                                        '<td class="text-right">' +
                                        '<h5><strong class="total">' + total
                                        .toLocaleString() +
                                        ' Tsh</strong></h5>' +
                                        '</td>' +
                                        '</tr>' + '<tr class="">' +
                                        '<tr class="select-customer">' +
                                        '<td colspan="2"></td>' +
                                        '<td  class="text-center">' +
                                        '<select class="form-control customer-select">' +
                                        '<option value=""> -- Select Customer -- </option>';
                                    $.each(cartData.customers, function(index,
                                        value) {
                                        newTable += '<option value="' +
                                            value.id + '">' + value.name +
                                            '</option>';
                                    });
                                    newTable += '</select>' +
                                        '</td>' +
                                        '<td></td>' +
                                        '</tr>' +
                                        '<tr class="">' +
                                        '<td colspan="5" class="text-center ">' +
                                        '<a href="{{ route('empty.cart') }}" class="btn btn-danger btn-sm my-2 mx-2"><i class=""></i>Empty Cart</a>' +
                                        '<button class="btn btn-sm my-2 mx-2 btn-success checkout-btn">RECORD PAID SALE</button>' +
                                        '<button class="btn btn-sm my-2 mx-2 btn-warning unpaid-btn">SAVE UNPAID SALE</button>' +
                                        '</td>' +
                                        '</tr>';
                                    '</tfoot>' +
                                    '</table>';
                                    $('#cart-container').html(newTable);

                                }
                            });
                        }
                    }
                });
                $("#cartCollapse").collapse("show");
            });

        });
    </script>
    <script>
        jQuery(document).ready(function() {
            $('#cart-container').on('change', '.update-cart', function(event) {

                event.preventDefault();

                var ele = $(this);
                var id = $(this).closest('tr').data('id');
                var quantity = ele.val();
                console.log(quantity);

                $.ajax({
                    url: '{{ route('update.cart') }}',
                    method: "patch",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.parents("tr").attr("data-id"),
                        quantity: ele.parents("tr").find(".quantity").val()
                    },

                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            var ele = $("tr[data-id='" + id + "']");
                            ele.find('.price').text(data.newPrice.toLocaleString() + ' Tsh');
                            ele.find('.quantity').val(quantity);
                            var price = parseFloat(ele.find('.price').text().replace(",", ""));
                            var subtotal = data.newPrice * quantity;
                            ele.find('.subtotal').text(subtotal.toLocaleString() + ' Tsh');
                            var total = 0;
                            $('.subtotal').each(function() {
                                total += parseFloat($(this).text().replace(",", ""));
                            });
                            $('.total').text(data.total + ' Tsh');
                        }
                    }
                });
                $("#cartCollapse").collapse("show");

            });
        });
    </script>

    <script>
        $('#cart-container').on('click', '.remove-from-cart', function(event) {
            event.preventDefault();

            var ele = $(this).closest('tr');
            var id = ele.attr("data-id");
            $.ajax({
                url: '{{ route('remove.from.cart') }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.attr("data-id")
                },
                success: function(data) {
                    if (data.success) {
                        console.log(data);
                        $('.cart-dropdown .btn .badge .btn').text(data.count);
                        $('.cart-count').text(data.count + ' Products');

                        ele.remove();
                        var total = 0;
                        $('.subtotal').each(function() {
                            total += parseFloat($(this).text().replace(",", ""));
                        });
                        $('.total').text(data.total);

                    }
                }

            });
        });
    </script>

    <script>
        document.querySelectorAll('.nav-tabs a').forEach(tab => {
            tab.addEventListener('click', function() {
                localStorage.setItem('activeTab', this.id);
            });
        });

        const activeTab = localStorage.getItem('activeTab');
        console.log(activeTab);
        if (activeTab) {
            document.querySelectorAll('.nav-tabs a').forEach(tab => {
                if (tab.id === activeTab) {
                    tab.classList.add('active');
                    tab.setAttribute('aria-selected', true);
                } else {
                    tab.classList.remove('active');
                    tab.setAttribute('aria-selected', false);
                }
            });
        }
        var tab1Button = document.getElementById("tab1-button");
        var tab2Button = document.getElementById("tab2-button");
        var tab1Content = document.getElementById("tab1-content");
        var tab2Content = document.getElementById("tab2-content");
        if (activeTab == "tab1-button") {
            tab1Content.style.display = "block";
            tab2Content.style.display = "none";
            tab1Button.style.background = "var(--first-color)";
            tab1Button.style.color = "white";
            tab2Button.style.color = "var(--first-color)";
            tab2Button.style.background = "transparent";
        } else {
            tab2Content.style.display = "block";
            tab1Content.style.display = "none";
            tab2Button.style.background = "var(--first-color)";
            tab2Button.style.color = "white";
            tab1Button.style.color = "var(--first-color)";
            tab1Button.style.background = "transparent";
        }

        tab1Button.addEventListener("click", function() {
            tab1Button.style.background = "var(--first-color)";
            tab1Button.style.color = "white";
            tab2Button.style.color = "var(--first-color)";
            tab2Button.style.background = "transparent";
            tab1Content.style.display = "block";
            tab2Content.style.display = "none";
        });

        tab2Button.addEventListener("click", function() {
            tab2Button.style.background = "var(--first-color)";
            tab2Button.style.color = "white";
            tab1Button.style.color = "var(--first-color)";
            tab1Button.style.background = "transparent";
            tab1Content.style.display = "none";
            tab2Content.style.display = "block";
        });
    </script>
    <script>
        $('#cart-container').on('click', '.checkout-btn', function(e) {
            e.preventDefault();
            var customerId = $('.customer-select').val();

            if (customerId) {
                var confirmMessage = "Are you sure you want to checkout with this customer?";
            } else {
                var confirmMessage = "Are you sure you want to checkout without selecting a customer?";
            }

            if (window.confirm(confirmMessage)) {
                // User confirmed, proceed with checkout
                window.location.href = "sale-product?customer_id=" + (customerId || "") +
                    "&_token={{ csrf_token() }}";
            }
        });
    </script>

    <script>
        $('#cart-container').on('click', '.unpaid-btn', function(e) {
            e.preventDefault();
            var customerId = $('.customer-select').val();

            if (customerId) {
                var confirmMessage = "Are you sure you want to save unpaid transaction for this customer?";
            } else {
                var confirmMessage = "Are you sure you want to save unpaid transaction without selecting a customer?";
            }

            if (window.confirm(confirmMessage)) {
                window.location.href = "save-unpaid-product?customer_id=" + (customerId || "") +
                    "&_token={{ csrf_token() }}";
            }
        });
    </script>
@endsection
