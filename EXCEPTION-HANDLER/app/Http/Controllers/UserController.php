<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


Class UserController extends Controller {

    use ApiResponser;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function getUsers()
    {
        $users = User::all();
        return response()->json(['data' => $users], 200);
        
        //return $this->successResponse($users);
    }

    public function index()
    {
        $users = User::all();
        
        return $this->successResponse($users);
    }



    public function add(Request $request)
    {
        
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',
        ];

        $this->validate($request,$rules);

        $user = User::create($request->all());
        return $this->successResponse($user);
        //return response()->json($user, 200);
    }


    public function show($id)
    {
        $user =  User::findOrFail($id);
        return $this->successResponse($user);


        //$user = User::where('id', $id)->first();
      

        /*
        if($user){
            return $this->successResponse($user);
        }
        else{
            return $this->errorResponse('User ID Does Not Exists', Response::HTTP_NOT_FOUND);
        }*/
        
    }

    public function update(Request $request, $id)
    {
      $rules = [
        'username' => 'max:20',
        'password' => 'max:20',
        'gender' => 'in:Male,Female',
      ];

      $this->validate($request, $rules);
      $user = User::findOrFail($id);

      $user ->fill($request->all());

      // if no changes happen
      if ($user->isClean()){
        return response()->json('At least one value must change', 403);
      }else{
        $user->save();
        return $this->successResponse($user);
      }      
    }

    public function delete($id)
    {
      $user = User::findOrFail($id);
      $user->delete();
      return $this->successResponse($user);
    }
}