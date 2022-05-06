<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VerifyUser;
use Carbon\Carbon;
use Socialite;
use Auth;
use Exception;
use PDF;
use JWTAuth;

use App\Mail\ForgotMairest;
use App\Mail\SendMailreset;
use Illuminate\Support\Facades\Mail;

class UserAuth extends Controller
{
//   public function __construct() {
//         $this->middleware('auth:api', ['except' => ['login', 'register','buyer_register','verify_user','forgot_password','reset_assword']]);
//   }


    public function sendEmail($email,$user_id,$token) 

  {
      $title = '[Confirmation] Thank you for your register';
      $user_id = ['uid'=>$user_id];
      $details = [ 'token' => $token];
       $sendmail = Mail::to($email)->send(new SendMailreset($title, $user_id, $details));
           if (empty($sendmail)) 
          { 
              return response()->json(['message' => 'Mail Sent Sucssfully'], 200); 
          }else{
               return response()->json(['message' => 'Mail Sent fail'], 400); 
              } 
          }
          
     public function register(Request $request){

         $response=[];

        if($request['full_name'] ==''){
            $response['full_name']= 'Please enter full name';
        }

        if($request['user_name'] ==''){
            $response['user_name']= 'Please enter user name';
        }

        if($request['user_email'] ==''){
            $response['user_email']= 'Please enter email address';
        }

        if($request['password'] ==''){
            $response['password']= 'Please enter password';
        }

        if($request['phone'] ==''){
            $response['phone']= 'Please enter phone number';
        }
        
        if($request['gender'] ==''){
            $response['gender']= 'Please enter gender';
        }

        if($request['location'] ==''){
            $response['location']= 'Please enter location';
        }
        if($request['preferred_language'] ==''){
            $response['preferred_language']= 'Please enter preferred language';
        }

        if($request['i_am_a'] ==''){
            $response['i_am_a']= 'This field is required';
        }

        if($request['affiliation'] ==''){
            $response['affiliation']= 'Please select affiliation';
        }

        if($request['subject'] ==''){
            $response['subject']= 'Please select subject';
        }

        if($request['age_group'] ==''){
            $response['age_group']= 'Please select group';
        }

        // if($request['talent'] ==''){
        //     $response['talent']= 'Please select taslent';
        // }

        if($request['organization'] ==''){
            $response['organization']= 'Please Enter School/Organization';
        }

        if($request['seller_ref_name'] ==''){
            $response['seller_ref_name']= 'Please Enter 1 Name';
        }

        if($request['seller_ref_email'] ==''){
            $response['seller_ref_email']= 'Please Enter 1 Email';
        }

        if($request['seller_ref_phonenumber'] ==''){
            $response['seller_ref_phonenumber']= 'Please Enter 1 Phone number';
        }

        if($request['seller_ref_two_name'] ==''){
            $response['seller_ref_two_name']= 'Please Enter 2 Name';
        }

        if($request['seller_ref_two_email'] ==''){
            $response['seller_ref_two_email']= 'Please Enter 2 Email';
        }

        if($request['seller_ref_two_phonenumber'] ==''){
            $response['seller_ref_two_phonenumber']= 'Please Enter 2 Phone Number';
        }
        

        if(count($response)){
            $data['status']= 'error';
            $data['error']= 403;
            $data['result']= $response;

            return response()->json($data);
         }else{
            try{
              $get_result = User::where('user_email', '=', $request->user_email)->first();

            if($get_result){
                
                $data['status']= 'error';
                $data['error']= 403;
                $data['result']= 'Already register with this Email';
                return response()->json($data);
             }else{

              if($request->hasFile('sample_content')) {
                    $path = $request->file('sample_content')->store('/images/content');
                }
                $random_number = rand(10,100000);
                $Finaltoken = 'daatt'.$random_number.'token'.time();
                $role = 3;
                $approved_by_admin = 0;
              $user = User::Create([
                'full_name' => $request->full_name,
                'user_name' => $request->user_name,
                'user_email' => $request->user_email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'gender' => $request->gender,
                'location'=> $request->location,
                'preferred_language'=> $request->preferred_language,
                'i_am_a'=> $request->i_am_a,
                'preferred_language'=> $request->preferred_language,
                'affiliation'=> $request->affiliation,
                'subject'=> $request->subject,
                'age_group'=> $request->age_group,
                'talent'=> $request->talent ?? null,
                'sample_content'=> $path ?? null,
                'resourse_name' => $request->resourse_name ?? null,
                'resourse_one_name'  => $request->resourse_one_name ?? null,
                'resourse_one_email'  => $request->resourse_one_email ?? null,
                'resourse_one_phonenumber'  => $request->resourse_one_phonenumber ?? null,
                'resourse_two_name'  => $request->resourse_two_name ?? null,
                'resourse_two_email'  => $request->resourse_two_email ?? null,
                'resourse_two_phonenumber'  => $request->resourse_two_phonenumber ?? null,
                'verified' => 0,
                'organization' => $request->organization ,
                'seller_ref_name' => $request->seller_ref_name ?? null,
                'seller_ref_email' => $request->seller_ref_email ?? null,
                'seller_ref_phonenumber' => $request->seller_ref_phonenumber ?? null,
                'seller_ref_two_name' => $request->seller_ref_two_name ?? null,
                'seller_ref_two_email' => $request->seller_ref_two_email ?? null,
                'seller_ref_two_phonenumber' => $request->seller_ref_two_phonenumber ?? null,
                'token' => $Finaltoken,
                'role' => $role,
                'approved_by_admin' => $approved_by_admin,
                'createdDate'  => date('H:i:s'),
                'user_status' => 1,
            ]);

            if($user){
                $this->sendEmail($request->user_email,$user->id,$user->token);
              $data['status']= 201;
              $data['success']= 'User successfully register Please check for verified email!';
              $data['user']= $user;
              return response()->json($data);

               }
               
             }

            }catch(Request $e){
                return "error";
            }
       }
      
    }

    public function userRegister(Request $request){
        $response=[];

        if($request['full_name'] ==''){
            $response['full_name']= 'Please enter full name';
        }

        if($request['user_name'] ==''){
            $response['user_name']= 'Please enter user name';
        }

        if($request['user_email'] ==''){
            $response['user_email']= 'Please enter email address';
        }

        if($request['password'] ==''){
            $response['password']= 'Please enter password';
        }
         
        if($request['phone'] ==''){
            $response['phone']= 'Please enter phone number';
        }
        
        if($request['gender'] ==''){
            $response['gender']= 'Please enter gender';
        }

        if($request['location'] ==''){
            $response['location']= 'Please enter location';
        }
        if($request['preferred_language'] ==''){
            $response['preferred_language']= 'Please enter preferred language';
        }

        if($request['i_am_a'] ==''){
            $response['i_am_a']= 'This field is required';
        }

        if($request['affiliation'] ==''){
            $response['affiliation']= 'Please select affiliation';
        }

        if($request['age_group'] ==''){
            $response['age_group']= 'Please select age_group';
        }

        if($request['subject'] ==''){
            $response['subject']= 'Please select subject';
        }

        if(count($response)){
            $data['status']= 'error';
            $data['error']= 403;
            $data['result']= $response;
            return response()->json($data);
         }else{
             try{
                $get_result = User::where('user_email', '=', $request->user_email)->first();

                if($get_result){
                    $data['status']= 'error';
                    $data['error']= 403;
                    $data['result']= 'Already register with this Email';
                    return response()->json($data);
                 }else{
                    $random_number = rand(10,100000);
                    $Finaltoken = 'daatt'.$random_number.'token'.time();
                    $role = 2;
                    $user = User::Create([
                        'full_name' => $request->full_name,
                        'user_name' => $request->user_name,
                        'user_email' => $request->user_email,
                        'password' => Hash::make($request->password),
                        'phone' => $request->phone,
                        'gender' => $request->gender,
                        'location'=> $request->location,
                        'preferred_language'=> $request->preferred_language,
                        'i_am_a'=> $request->i_am_a,
                        'affiliation'=> $request->affiliation,
                        'subject'=> $request->subject,
                        'age_group'=> $request->age_group,
                        'verified' => 0,
                        'token' => $Finaltoken,
                        'role' => $role,
                        'createdDate'  => date('H:i:s'),
                        'user_status' => 1,
                    ]);

                    if($user){
                        $this->sendEmail($request->user_email,$user->id,$user->token);
                      $data['status']= 201;
                      $data['success']= 'User successfully register Please check for verified email!';
                      $data['user']= $user;
                      return response()->json($data);
        
                       }

                 }
             }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
                }
         }

    }
    
     public function login(Request $request){

        $response=[];

        if($request['user_email'] ==''){
            $response['user_email']= 'email field is required';
        }

        if($request['password'] ==''){
            $response['password']= 'password field is required';
        }

         if(count($response)){
            $data['status']= 'error';
            $data['error']= 403;
            $data['result']=$response;
            return response()->json($data);
         }else{
             
        $credentials = $request->only(['user_email', 'password']);
        $user_data =User::where('user_email', '=', $request->user_email)->where('verified','=',1)->first();
        if($user_data){
            $pass = Hash::check($request->password, $user_data->password);
            if ($pass) {
                $token = Auth::guard('api')->attempt($credentials);
                $user = JWTAuth::user();
                if($token){
                    $data['status']= 201;
                    $data['access_token']= $token;
                    $data['user_role']= $user['role'];
                    $data['token_type']= 'bearer';
                    $data['expires_in']= auth()->factory()->getTTL() * 60;
                    $data['result']='Successfull Login';
                   return response()->json($data);
                }else{
                    $data['status']= 'error';
                    $data['error']= 401;
                    $data['result']='Unauthorized token';
                   return response()->json($data);
                }
              } else {
                $data['status']= 'error';
                $data['error']= 400;
                $data['result']='Password is incorrect';
                return response()->json($data);
              }
        }else{
            $data['status']= 'error';
            $data['error']= 400;
            $data['result']='Sorry your email cannot be verified or email not valid!';
            return response()->json($data);
         }
      }
        
    }
    
    public function logout(Request $request){
        try {
            Auth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback(Request $request){
            try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $existUser = User::where('user_email', $googleUser->email)->first();
            
            if ($existUser) {
               $user_email =$existUser->email;
               $user_password ="my-google";
               $credentials = ['user_email'=>$user_email,'password'=>$user_password];
               $token = auth()->attempt($credentials);
                if($token){
                   return response()->json([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]);
               }
            }else {

                $user = User::Create([
                 'user_login' => $googleUser->name,
                 'user_email' => $googleUser->email,
                 'display_name'=> $googleUser->name,
                 'password' => Hash::make('my-google'),
                 'google_id' => $googleUser->id
                ]);
                
               $user_email =$googleUser->email;
               $user_password ="my-google";
               $credentials = ['user_email'=>$user_email,'password'=>$user_password];
               $token = auth()->attempt($credentials);
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
           ]);
         }

      }catch (Exception $e) {
        return 'error';
    }
  }


  public function verifyEmail(Request $request){

   $validator = Validator::make($request->all(), [ 
            'user_email' => 'required' 
             
        ]);
    
        if ($validator->fails()) { 
            return [
                 'error' => true, 
                 'message' => $validator->errors()->first()];           
        }
        $user=User::where('user_email','=',$request->user_email)->first();
        try{
        if($user){
            $data = [
                'email' => $user->user_email,
                'url' => url("/reset_password/{$user->id}")
            ];
         
          Mail::send('emails.verify_email', $data, function($message)use($data) {
             $message->to($data['email'], 'Daatt Group')
             ->subject('Invoice : Daatt Group of Educators');
         });
         return response()->json([
                'success' => true,
                'message' => 'mail send seccussfully!.'
            ]);
        }else{
          return ['error'=>false,'exist'=>false];
        } 

        }catch(Exception $e){

            return "error";
        }

  }
 

  public function resetPassword(Request $request,$id){
        $response=[];

        if($request['password'] ==''){
            $response['password']= 'password field is required';
        }

        if($request['c_password'] ==''){
            $response['c_password']= 'confirm field is required';
        }


        if($request['password'] !==$request['c_password']){
            $response['c_password']= 'password  and confirm password do not match';
        }

        if(count($response)){
            $response['error']= true;
            return response()->json($response);
          }else{

        $user_data=User::where('id','=',$id)->first();

        if($user_data){

        $users_update = User::where('id',$user_data->id)
        ->update([
        'password' =>Hash::make($request->password)
        ]);

        return response()->json(['error'=>false,'msg'=>"password reset successfully!"]);

      }else{
        return response()->json(['error'=>true,'msg'=>"some thing went wrong!"]);
      }

     }
  }

   public function sendmail(Request $request){
    $data = ['email' => $request->user_email];
         // $data = ['email' => $request->user_email];

          $pdf = PDF::loadView('emails.daattemail',$data);
        
         Mail::send('emails.daattemail', $data, function($message)use($pdf,$data) {
             $message->to($data['email'], 'Daatt Group')
             ->subject('Invoice : Daatt Group of Educators')
             ->attachData($pdf->output(),"text.pdf");
         });
         return "send mail seccussfully!.";
   }

   public function downloadPdf(Request $request){
     $pdf = PDF::loadView('emails.daattemail'); 

     return $pdf->download('test.pdf');
   }
   
    public function verify_user($token){
       try{
        $get_result = User::where('token', '=', $token)->first();
        if($get_result){
            $get_result->verified = 1;
            $get_result->save();
            return view('confirm')->with('successMsg','Thanks for vrifing');
        }else{
            $data['status']= 'error';
            $data['error']= 201;
            $data['result']='Your e-mail is verified. You can now login.';
            return response()->json($data);
          
        }

       }catch(Request $e){
        return "error";
       }
    }
    
      public function forgotEmail($email,$token) 

    {
        $title = '[Confirmation] Daatt forgot password';
        $details = [ 'token' => $token];
         $sendmail = Mail::to($email)->send(new ForgotMairest($title, $details));
             if (empty($sendmail)) 
            { 
                return response()->json(['message' => 'Mail Sent Sucssfully'], 200); 
            }else{
                 return response()->json(['message' => 'Mail Sent fail'], 400); 
                } 
            }
            
    
    public function forgot_password(Request $request)
    {
        $get_result = User::where('user_email', $request->user_email)->first();
        if($get_result){
            $random_number = rand(10,100000);
            $Finaltoken = $random_number.'zaxbnhyihh'.$random_number.'dgrfewskgfiskfjfk'.time();
            $this->saveToken($Finaltoken,$request->user_email);
            $this->forgotEmail($request->user_email,$Finaltoken);
                  $data['status']= 200;
                  $data['success']= "Reset email link sent successfully, please check your inbox";
                  return response()->json($data,200);
        }else{
            $data['status']= 'error';
            $data['error']= 404;
            $data['result']='Email was not found';
            return response()->json($data,404);
        }
    }
    public function saveToken($token, $email){
        User::where('user_email',$email)->update([
            'token' => $token,
            'createdDate' => Carbon::now()
        ]);

    }

    
    
    public function reset_assword(Request $request){

        $response=[];

        if($request['password'] ==''){
            $response['password']= 'password field is required';
        }
        
        if($request['token'] ==''){
            $response['token']= 'token field is required';
        }

        if(count($response)){
            $data['status']= 'error';
            $data['error']= 401;
            $data['result']=$response;
            return response()->json($response,401);
          }else{

            try{

        $user_data=User::where('token','=',$request->token)->first();

        if($user_data){

        $users_update = User::where('user_email',$user_data->user_email)
        ->update([
        'password' => Hash::make($request->password)
        ]);
        $data['status']= 200;
        $data['success']= "password reset successfully!";
        return response()->json($data,200);

        }else{
            $data['status']= 'error';
            $data['error']= 401;
            $data['result']="some thing went wrong!";
            return response()->json($response,401);
         }
         }catch(Exception $e){
            return "error";
        }

     }
    }

    
    

}

