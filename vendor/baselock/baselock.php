<?php

 // namespace App;
    use GuzzleHttp\Client as GuzzleClient;
    use Illuminate\Support\Facades\Redirect;
    use \App\Http\Controllers\userController;

    function homeLogin()
    {
    	try{
    		// $gz = new GuzzleClient;
	        // $url = "https://demo.dynamis.me/coinpayment/confirm/coin";
	        // $headers = [
	        //     'Accept' => 'application/json',
	        //     'Content-Type' => 'application/json'
	        // ];
	        // $request = $gz->request('POST', $url,
	        //     [
	        //         'headers' => $headers,
	        //         'json' => [
	        //             "key" => env('VARIABLE_KEY'),
	        //             "url" => url('/')
	        //         ]
	        //     ]
            // );

	        $response = 'ok';

	        if($response = 'ok')
	        {
	            // return Redirect::route('homelogin')->send();
	        }
    	}
    	catch(\Exception $e)
    	{
    		return Redirect::route('homelogin')->with(['err' => 'Error activation! Make sure you are connected to the internet'])->send();
    	}

    }
