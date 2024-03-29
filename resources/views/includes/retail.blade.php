<div class="row">
    <div class="col-md-6">
        @if ($products != null)
            @foreach ($stocks as $index => $stock)
                <div class="card my-2"
                    style="background-image: linear-gradient(to right, white,rgb(212, 208, 238));">
                    <div class="card-body my-1 py-0">
                        <div class="row"style="align-items:center;">
                            <div class="col-md-6">
                                <div style="background:white;">
                                    <h4 style="font-weight:bolder;font-size: 22px;">{{ $stock->name }} -
                                        {{ $stock->volume }} {{ $stock->measure }}</h4>
                                    <div style="color:rgb(118, 7, 7); font-size: 15px;">Remained Quantity:
                                        <b>{{ $stock->quantity }} </b> {{ $stock->unit }}
                                    </div>
                                    <a href="{{ route('stocks.show', $stock) }}"
                                        style="text-decoration: none">View</a>
                                </div>
                            </div>
                            <div hidden class="col-md-6 ">
                                @if ($stock->product->status == 1)
                                    <div class="py-2 px-1 my-1 shadow text-center"
                                        style="mazx-width:260px; background:white;  border-radius:5px;">
                                        <div class="row pb-2">
                                            <span class="col text-start "
                                                style="color: green; font-weight:bold;">
                                                {{ $stock->product->volume . ' ' . $stock->product->measure }}
                                            </span>
                                            <span class="col-1"></span>
                                            <span class="col text-end" style="color: rgb(243, 119, 4);">
                                                <b>
                                                    {{ number_format($stock->product->price, 0, '.', ',') }}</b>
                                                Tsh
                                            </span>
                                        </div>
                                        @if ($stock->quantity < 1)
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                title="{{ $stock->product->name . ' sold out. Only ' . $stock->volume . ' ' . $stock->unit . ' remained.' }}">
                                                <button href="#" class="btn btn-sm btn-primary my-1"
                                                    style="opacity: var(--bs-btn-disabled-opacity);cursor:auto;pointer-events: none;">
                                                    {{ 1 . ' ' . $stock->product->unit }}
                                                </button>
                                            </span>
                                        @else
                                            <a data-id="{{ $stock->product->id }}"
                                                href="{{ route('add.to.cart', $stock->product->id) }}"id="add-to-cart-{{ $stock->id }}"
                                                class="btn btn-sm btn-primary my-1 add-to-cart-form"
                                                value="{{ $stock->product->id }}" role="button">
                                                {{ 'Add to Cart' }}
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-md-6">
        <div id="cartCollapse" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
            aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="card">
                    <div class="card-header text-center"
                        style="color: var(--first-color);font-weight:bold;font-size:20px">
                        Cart - <span class="cart-count"
                            style="background: rgb(206, 197, 237); border-radius:5px;padding-left:5px;padding-right:5px;">{{ count((array) session('cart')) }}
                            Products</span>
                    </div>

                    <div class="card-body" id="cart-container"></div>

                </div>
            </div>
        </div>
    </div>
</div>
