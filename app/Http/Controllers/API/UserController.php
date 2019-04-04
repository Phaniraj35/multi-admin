<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => "required|string|max:191",
            'email' => "required|string|email|max:255|unique:users",
            'password' => "required|string|min:6",
        ]);

        return User::create([

            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'password' => Hash::make($request['password']),
            'bio' => $request['bio'],
            'photo' => $request['photo']

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    public function profile()
    {
        return auth('api')->user();
    }



    public function updateProfile(Request $request)
    {

        $user = auth('api')->user();

        $request->validate([
            'name' => "required|string|max:191",
            'email' => "required|string|email|max:255|unique:users,email,".$user->id,
            'password' => "sometimes|string|min:6",
        ]);


        $currentPhoto = $user->photo;
         if($request->photo != $currentPhoto) {
             $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];


             \Image::make($request->photo)->save(public_path('img/profile/').$name);
             $request->merge(['photo'=>$name]);

             $currentPhotoPath = public_path('img/profile/').$currentPhoto;

             if(file_exists($currentPhotoPath))
                @unlink($currentPhotoPath);

         }


         if(!empty($request->password)) {
            $request->merge(['password'=>Hash::make($request['password'])]);
         }

         $user->update($request->all());
         return ['message' => 'Profile editing successful'];
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $request->validate([
            'name' => "required|string|max:191",
            'email' => "required|string|email|max:255|unique:users,email,".$user->id,
            'password' => "sometimes|string|min:6",
        ]);


        $user->update($request->all());


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);

        $user->delete();

        return ["message" => 'User Deleted'];
    }



     public function search(Request $request){
        if ($search =$request->get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }
        return $users;
    }
}
