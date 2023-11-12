<div class="row">
    @if ($products != null)
        @foreach ($stocks as $index => $stock)
            <div class="col-md-4">
                <div class="card mb-2" style="background-image: linear-gradient(to right, white,rgb(212, 208, 238));">
                    <div class="card-body  py-0">
                        <div class="row" style="align-items:center;">
                            <div class="col-md-8">
                                <div style="background:white;">
                                    <h4 style="font-weight:bolder;font-size: 20px;">{{ $stock->name }} -
                                        {{ $stock->volume }} {{ $stock->measure }}</h4>
                                    <div style="color:rgb(118, 7, 7); font-size: 15px;">Remained Quantity:
                                        <b>{{ $stock->quantity }} </b> {{ $stock->unit }}
                                    </div>
                                    <a href="{{ route('stocks.show', $stock) }}" style="text-decoration: none">View</a>
                                </div>
                            </div>
                            <div class="col-md-4 ">
                                @if ($stock->product->status == 1)
                                    <div class="py-2  my-1 shadow text-center"
                                        style="background:white;  border-radius:5px;">
                                        <div class="col text-center" style="color: green; font-size:20px">
                                            <b>
                                                {{ number_format($stock->product->special_price, 0, '.', ',') }}</b>
                                            Tsh
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
                                                href="{{ route('add.to.special_cart', $stock->product->id) }}"
                                                id="add-to-cart-{{ $stock->id }}"
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
            </div>
        @endforeach
    @endif
</div>

<div id="cartCollapse" class="accordion-collapse collapse" aria-labelledby="headingTwo"
    data-bs-parent="#accordionExample"
    style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); z-index: 999;">
    <div class="accordion-body">
        <div class="card">
            <div class="card-header text-center" style="color: var(--first-color);font-weight:bold;font-size:20px">
                Special Cart - <span class="cart-count"
                    style="background: rgb(206, 197, 237); border-radius:5px;padding-left:5px;padding-right:5px;">{{ count((array) session('special_cart')) }}
                    Products</span>
            </div>
            <div class="card-body" style="background-color: rgba(229, 213, 240, 0.493)" id="cart-container"></div>
        </div>
    </div>
</div>



<script>
    // Add JavaScript code to make cartCollapse draggable
    const cartCollapse = document.querySelector('#cartCollapse');
    let isDragging = false;
    let dragX = 0;
    let dragY = 0;

    cartCollapse.addEventListener('mousedown', function(e) {
        isDragging = true;
        dragX = e.clientX - cartCollapse.offsetLeft;
        dragY = e.clientY - cartCollapse.offsetTop;
    });

    document.addEventListener('mousemove', function(e) {
        if (isDragging) {
            cartCollapse.style.left = e.clientX - dragX + 'px';
            cartCollapse.style.top = e.clientY - dragY + 'px';
        }
    });

    document.addEventListener('mouseup', function(e) {
        isDragging = false;
    });
</script>
