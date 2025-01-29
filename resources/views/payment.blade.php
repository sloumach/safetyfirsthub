<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>payment</title>
</head>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-jOnm9F82uI9EXb3GWym+lHvR0khn6UQe8a6r61DDB80DXH5vYUkkKw7CMX2DqaCE" crossorigin="anonymous">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .class-header {
      background-color: #007bff;
      color: white;
      padding: 15px;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      text-align: center;
    }
    .bg-light {
      border-radius: 10px;
      padding: 15px;
    }
    #card-element {
      background: white;
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 10px;
      margin-top: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    #card-element:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    #submit-button {
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    #submit-button:hover {
      background-color: #0056b3;
    }
    #payment-message {
      margin-top: 15px;
      font-size: 14px;
      color: red;
    }
  </style>

  <div class="container col-md-4">
    <div class="card">
      <div class="class-header">
        <h4>Make Payment</h4>
      </div>
      <div class="card-body">
        <div class="p-3 bg-light bg-opacity-10">
          <h6 class="card-title mb-3">Order Summary</h6>
          <div class="d-flex justify-content-between mb-1 small">
            <span>Subtotal</span><span>${{ $totalPrice }}</span>
          </div>
          <div class="d-flex justify-content-between mb-1 small">
            <span>Shipping</span><span>$0</span>
          </div>
          <div class="d-flex justify-content-between mb-1 small">
            <span>Total</span><span>${{ $totalPrice }}</span>
          </div>
        </div>
      </div>
    </div>

    <form action="{{ route('charge') }}" method="post" id="stripeform" class="mt-4">
    @csrf
    <div class="p-3 border rounded bg-white">
        <!-- Card Header -->
        <h6 class="mb-3 text-center fw-bold fs-5">Card Details</h6>

      

        <!-- Card Number Field with Icons -->
        <div class="mb-2">
            <label class="form-label">Card Number</label>
            <div class="d-flex align-items-center border rounded px-2 py-1">
                <span class="me-2">💳</span>
                <input type="text" id="card-number" class="form-control border-0" placeholder="1234 1234 1234 1234">
            </div>
        </div>

        <!-- Supported Card Icons -->
        <div class="d-flex justify-content-start mb-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" width="50" class="me-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="MasterCard" width="50" class="me-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" width="50" class="me-2">
        </div>
        <small class="text-muted">Supported cards include Visa, Mastercard, American Express, and Discover.</small>

        <!-- Expiration Date & Security Code -->
        <div class="row mt-3">
            <div class="col-6">
                <label class="form-label">Expiration Date</label>
                <input type="text" class="form-control" placeholder="MM / YY">
            </div>
            <div class="col-6">
                <label class="form-label">Security Code</label>
                <input type="text" class="form-control" placeholder="CVV">
            </div>
        </div>

          <!-- Country Selection -->
          <div class="mb-2">
            <label class="form-label">Country</label>
            <select class="form-select" name="country" required>
                <option value="">Select your country</option>
                <option value="US">United States</option>
                <option value="CA">Canada</option>
                <option value="GB">United Kingdom</option>
                <option value="AU">Australia</option>
                <option value="DE">Germany</option>
                <option value="FR">France</option>
                <option value="IN">India</option>
                <option value="JP">Japan</option>
                <option value="CN">China</option>
                <option value="BR">Brazil</option>
                <!-- Add more countries as needed -->
            </select>
        </div>
        <!-- Hidden Inputs -->
        <input type="hidden" name="price" id="paymentMethod" value="100">
        <input type="hidden" name="token" id="stripetoken">

        <!-- Pay Button -->
        <button class="btn btn-primary w-100 mt-3" id="submit-button" type="button">Pay</button>
    </div>
</form>
<div id="payment-message"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    const stripe = Stripe('{{ env('stripe_public_key') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
      style: {
        base: {
          color: '#32325d',
          fontFamily: 'Arial, sans-serif',
          fontSmoothing: 'antialiased',
          fontSize: '16px',
          '::placeholder': { color: '#aab7c4' },
        },
        invalid: {
          color: '#fa755a',
          iconColor: '#fa755a',
        },
      },
    });
    cardElement.mount('#card-element');
    document.getElementById('submit-button').addEventListener('click', async (e) => {
      e.preventDefault();
      const { clientSecret, error } = await fetch('{{ route('charge') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ amount: 50, description: 'Achat de cours' }),
      }).then((res) => res.json());
      if (error) {
        document.getElementById('payment-message').innerText = `Error: ${error}`;
        return;
      }
      const { paymentIntent, error: stripeError } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: { card: cardElement },
      });
      if (stripeError) {
        document.getElementById('payment-message').innerText = `Payment failed: ${stripeError.message}`;
      } else {
        document.getElementById('payment-message').innerText = 'Payment succeeded!';
      }
    });
  </script>





</body>
</html>
