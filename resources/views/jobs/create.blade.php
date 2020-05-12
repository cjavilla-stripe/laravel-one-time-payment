<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title></title>
    <link href="https://stripe-samples.github.io/developer-office-hours/demo.css" rel="stylesheet" type="text/css">
<style>
.is-invalid {
  background-color: red;
}
</style>
  </head>
  <body>
    <div id="main">
      <div id="container">
        <div id="panel">

          <form action="/jobs" method="post" id="job-create-form">
            @csrf
            <fieldset>
              <label>
                <span>Title</span>
                <input type="text" class="field" name="title" id="title-input" value="Software Engineer">
                <span id="title-error"></span>
              </label>
              <label>
                <span>Type</span>
                <input type="text" class="field" name="job_type" id="type-input" value="standard">
              </label>
              <label>
                <div class="field" id="card-element"></div>
              </label>

              <input type="hidden" name="payment_intent" id="payment_intent">

              <div id="error-messages"></div>

              <button type="submit" id="btn">Create Job</button>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script charset="utf-8">
      var stripe = Stripe('pk_test_vAZ3gh1LcuM7fW4rKNvqafgB00DR9RKOjN');
      var elements = stripe.elements();
      var cardElement = elements.create('card');
      cardElement.mount('#card-element');
      var form = document.getElementById('job-create-form');
      var title = document.getElementById('title-input');
      var titleError = document.getElementById('title-error');
      var jobType = document.getElementById('type-input');
      var paymentIntentInput = document.getElementById('payment_intent');

      form.addEventListener('submit', function(e) {
        e.preventDefault();
        createPaymentIntent().then(function(paymentIntent) {
          paymentIntentInput.value = paymentIntent.id;

          if(paymentIntent.errors) {
            showErrors(paymentIntent.errors);
          } else {
            errorMessages.innerText = '';
            confirmPaymentIntent(paymentIntent).then(function() {
              submitForm(paymentIntent);
            });
          }
        });
      });

      function submitForm(paymentIntent) {
        form.submit();
      }

      function confirmPaymentIntent(paymentIntent) {
        return stripe.confirmCardPayment(
          paymentIntent.clientSecret,
          {
            payment_method: {
              card: cardElement,
            },
          }
        );
      }

      var errorMessages = document.getElementById('error-messages');
      function showErrors(errors) {
        if(errors.title) {
          title.classList.add('is-invalid');
          titleError.innerText = errors.title.join(" ");
        }
        errorMessages.innerText = JSON.stringify(errors, null, 2)
      }

      function createPaymentIntent() {
        return fetch('/jobs/create_payment_intent', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.getElementsByName('_token')[0].value,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({
            title: title.value,
            job_type: jobType.value,
          }),
        })
        .then((response) => response.json())
        .catch((error) => {
          console.error('Error:', error);
        });
      }
    </script>
  </body>
</html>
