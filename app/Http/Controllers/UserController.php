<?php
    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Http\Request;
    use App\User;
    use App\Http\Resources\User as UserResource;

    class UserController extends Controller
    {
      public function __construct()
       {
         //  $this->middleware('auth:api');
       }

       public function create(Request $request) 
       {
         $this->validate($request, [
          'email' => 'required',
          'password' => 'required',
          'name' => 'required',
         ]);

         $salt = "lol it's salt";
         $storedPassword = $this->generatePasswordString($request->input('password'), $salt);
         $input = [
           'email' => $request->input('email'),
           'name' => $request->input('name')
         ];
          $user = new User;
          $user->fill($input);
          $user->password = $storedPassword;
          $user->salt = $salt;

          $user->save();
          $apiKey = $this->generateRandomString();
          $apiKey = "{$user->id} {$apiKey}"; //user id gives us the sureness of uniqueness
          $user->api_token = $apiKey;  
          $user->save();
          return (new UserResource($user))->additional(['meta' => [
            'apiToken' => $apiKey,
          ]]);  
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
              $user = User::where('email', $request->input('email'));
              $user->update(['api_token' => "$apiKey"]);
              $user->save();
              return (new UserResource($user))->additional(['meta' => [
                'api_token' => $apiKey,
              ]]); 
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

        return (new UserResource($request->user()));
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
          $input = $request->input();
          $user = $request->user();
          if (array_key_exists('password', $input)){
            $salt = "lol it's salt";
            $storedPassword = $this->generatePasswordString($request->input('password'), $salt);
            $apiKey = $this->generateRandomString();
            $apiKey = "{$user->id} {$apiKey}"; //user id gives us the sureness of uniqueness
            $user->salt = $salt;
            $user->password = $storedPassword;
            $user->api_token = $apiKey;
          }
          $user->fill($request->input());
          $user->save();

          $resource = (new UserResource($user));
          if (array_key_exists('password', $input)){
            return $resource->additional(['meta' => [
              'api_token' => $apiKey,
            ]]);
          }
          return $resource;
       }

       private function generateRandomString()
       {
          $randomString = '';
          for ($i = 0; $i < 40; $i++) {
            $randomString .= chr(random_int(61, 79)); //other characters seem to break request header data
          }
          return $randomString;
       }

       private function generatePasswordString($password, $salt)
       {
        return Hash::make($password.$salt);
       }
    }    
?>