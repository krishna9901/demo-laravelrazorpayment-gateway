# demo-laravelrazorpayment-gateway
Demonstrate how to Integrate Payment Gateway - RazorPay with Laravel


# demo-laravelrazorpayment-gateway
Demonstrate how to Integrate Payment Gateway - RazorPay with Laravel



Steps to Integrate RazorPay with Laravel

Follow the steps below.

Step 1 - Install the Laravel 8 Application
Go to the directory you want to install your project in the terminal and run the below command to install new Laravel project 

command ** composer create-project laravel/laravel RazorpayIntegrationLaravel**




Step 2 - Connect Database to an Application
In this step, open your project folder in vs. code and open .env and add your database name, user, and password like this.

laravel_razorpay
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_razorpay
DB_USERNAME=root
DB_PASSWORD=
```



Step 3 - Add RazorPay Credentials
If you already have Razorpay account, login into the Razorpay dashboard or create a new Razorpay account. Then go to settings from the left sidebar, and you will see the API KEYS tab.

Open that tab and generate a new key. Copy those keys, and paste them into your .env file.

```
RAZORPAY_KEY=rzp_test_XXXXXXX
RAZORPAY_SECRET=XXXXXXXXXXXXXX


```


Step 4 - Install the Composer Package of RazorPay


could you install the composer package of Razorpay? To install that, get the root directory of your project in the terminal and run the below command.



command  **composer require razorpay/razorpay**






Step 5 - Create Route
Open web.php and create a new route. 

RazorpayController
```
Route::get('product',[RazorpayController::class,'index']);
Route::post('razorpay-payment',[RazorpayController::class,'store'])->name('razorpay.payment.store');

```

Step 6 - Create Migration and Model


you need to create migration for new table payments to store the response of razorpay API. Also, create a model Payment for the same. Run this below command 

Command **php artisan make:model Payment --migration**




Copy this code in the migration file and run 



add these fileds tablefile.php
```
$table->increments('id');
    $table->string('r_payment_id');
    $table->string('method');
    $table->string('currency');
    $table->string('user_email');
    $table->string('amount');
    $table->longText('json_response');
    $table->timestamps();
Schema::create('payments',function(Blueprint $table) {
    $table->increments('id');
    $table->string('r_payment_id');
    $table->string('method');
    $table->string('currency');
    $table->string('user_email');
    $table->string('amount');
    $table->longText('json_response');
    $table->timestamps();
  });
    protected $table = 'payments';
    protected $guarded = ['id'];
```
Command **php artisan migrate**



```
<?php 
​




namespace App\Models;
​
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
​
class Payment extends Model {
    use HasFactory;
    protected $table = 'payments';
    protected $guarded = ['id'];
}

```


​
Step 7 - Create Controller


 create a controller using this command and write the below code. In the controller, we will write the logic for Razorpay API call and store the response in our database.
```
public function store(Request $request) {
    $input = $request->all();
    $api = new Api (env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    $payment = $api->payment->fetch($input['razorpay_payment_id']);
    if(count($input) && !empty($input['razorpay_payment_id'])) {
        try {
            $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
            $payment = Payment::create([
                'r_payment_id' => $response['id'],
                'method' => $response['method'],
                'currency' => $response['currency'],
                'user_email' => $response['email'],
                'amount' => $response['amount']/100,
                'json_response' => json_encode((array)$response)
            ]);
        } catch(Exceptio $e) {
            return $e->getMessage();
            Session::put('error',$e->getMessage());
            return redirect()->back();
        }
    }
    Session::put('success',('Payment Successful');
    return redirect()->back();

}
```

Step 8 - Create View File

Create Laravel blade file and add the below code in that file and call that file with the help of the view function from the controller. 

```
<div class="card card-default">
    <div class="card-header">
        Laravel - Razorpay Payment Gateway Integration
    </div>
    <div class="card-body text-center">
        <form action="{{ route('razorpay.payment.store') }}" method="POST" >
            @csrf 
            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="{{ env('RAZORPAY_KEY') }}"
                    data-amount="10000"
                    data-buttontext="Pay 100 INR"
                    data-name="GeekyAnts official"
                    data-description="Razorpay payment"
                    data-image="/images/logo-icon.png"
                    data-prefill.name="ABC"
                    data-prefill.email="abc@gmail.com"
                    data-theme.color="#ff7529">
            </script>
        </form>
    </div>
</div>

```
Now run this below command and open this URL http://localhost:8000/product

Command **PHP artisan serve**





