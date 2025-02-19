<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>payment</title>
</head>
<body>
    <div class="container col-md-4">
        <div class="card mt-5">
            <div class="class-header">
                <h4>make payment</h4>
            </div>
            <div class="card-body">
                <div class="p-3 bg-light bg-opacity-10">
                    <h6 class="card-title mb-3">order summary</h6>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>Subtotal</span><span>${{ $totalPrice }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>Shipping</span><span>$0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>total</span><span>${{ $totalPrice }}</span>
                    </div>
                </div>
            </div>
        </div>



            <label class="form-label fs-12 text-left" for="card-element">{{ __('Credit or Debit Card') }}</label>
            <form action="{{ route ('charge') }}" method="post" id="stripeform">
                @csrf
                <div class="form-group">

                    <div id="card-element" class="form-control" style='height: 2.4em; padding-top: .7em;'>
                      <!-- A Stripe Element will be inserted here. -->
                    </div>
                  </div>

                <input type="hidden" name="price" id="paymentMethod" value="100">

                <input type="hidden" name="token" id="stripetoken">
                <button class="btn btn-primary w-100 mt-2" id="submit-button" {{-- onclick="createtoken()" --}} type="button">pay</button>

            </form>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

   


</body>
</html>
