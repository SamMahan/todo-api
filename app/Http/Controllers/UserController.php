<?php
    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Http\Request;
    use App\User;

    class UserController extends Controller
    {
      public function __construct()
       {
         //  $this->middleware('auth:api');
       }

       /**
        * Display a listing of the resource.
        *
        * @return \Illuminate\Http\Response
        */
       public function login(Request $request)
       {
         try{
           $this->validate($request, [
           'email' => 'required',
           'password' => 'required'
            ]);
          $user = User::where('email', $request->input('email'))->first();
        //  if(Hash::check($request->input('password'), $user->password)){ //not hashing for now to ease dev strain
            if ($request->input('password') == $user->password) {
              $apiKey = $this->generateRandomString();
              $apiKey = "{$user->id} {$apiKey}"; //user id gives us the sureness of uniqueness
              User::where('email', $request->input('email'))->update(['api_token' => "$apiKey"]);
              return response()->json(['status' => 'success','api_token' => $apiKey]);
            } else {
              return response()->json(['status' => 'fail'],401);
            }
         }catch (\Exception $err) {
          return "error";
           error_log( print_r($err->getTrace(), true));
         }
       }

        /**
        * Display the current authenticated user
        *
        * @return \Illuminate\Http\Response
        */
       public function getCurrent(Request $request)
       {
        //  return response()->json(['lol' => 'lol']);
         return $request->user();
       }

       /**
        * Display the current authenticated user
        *
        * @return \Illuminate\Http\Response
        */
       public function update(Request $request)
       {
          $this->validate($request, [
            'email' => 'required',
            'name' => 'required'
          ]);
            $user = $request->user();
            $user->fill($request->input());
            $user->save();
       }

       private function generateRandomString()
       {
          $randomString = '';
          for ($i = 0; $i < 40; $i++) {
            $randomString .= chr(random_int(61, 79)); //other characters seem to break request header data
          }
          return $randomString;
       }
    }    
?>