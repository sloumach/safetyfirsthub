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
                        <span>Subtotal</span><span>$190.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>Shipping</span><span>$20.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>total</span><span>$210.00</span>
                    </div>
                </div>
            </div>
        </div>



            <label class="form-label fs-12 text-left" for="card-element">{{ __('Credit or Debit Card') }}</label>
            <form action="{{ route ('charge') }}" method="post" id="stripeform">
                @csrf
                <div class="form-control" id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <input type="hidden" name="price" id="paymentMethod" value="100">

                <input type="hidden" name="token" id="stripetoken">
                <button class="btn btn-primary w-100 mt-2" onclick="createtoken()" type="button">pay</button>

            </form>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://js.stripe.com/v3/"></script>
        <script type="text/javascript">
            // Create a Stripe client.
            var locale = '{{ app()->getLocale() }}';
            var stripe = Stripe("{{ env('stripe_public_key') }}");
            // Create an instance of Elements.
            var elements = stripe.elements();
            var cardelement = elements.create('card');
            cardelement.mount('#card-element');
            function createtoken(){
                stripe.createToken(cardelement).then(function(result) {
                console.log(result);
                if (result.token) {
                    document.getElementById('stripetoken').value  =result.token.id;
                    document.getElementById('stripeform').submit();
                }
            });
            }
            //var elements = stripe.elements( 'fr');
            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '14px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            //var card = elements.create('card', {hidePostalCode: true,disableLink:true, style: style});

            // Add an instance of the card Element into the `card-element` <div>.
            //card.mount('#card-element');
            // Sélectionne l'élément par son ID
         // Supprime l'élément du DOM


            // Handle real-time validation errors from the card Element.


            // Handle form submission.


        </script>



</body>
</html>
