<?php
namespace App\Http\Controllers;
use App\Password;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\User;
use App\Category;
class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id = User::where('email', $userData->email)->first()->id;
        $passwords = Password::where('user_id', $id)->get();
        if ($passwords->isEmpty()) { 
            return $this->error(400, "No hay contraseñas.");
        }
        $passwordTitles = [];
        $passwordIDs = [];
        foreach ($passwords as $password) {
            array_push($passwordTitles, $password->title);
            array_push($passwordIDs, $password->id);
            } 
        return response()->json ([
                'passwords' => $passwordTitles,
                'ids' => $passwordIDs,
            ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $passwordTitle = $_POST['passwordTitle'];
        $passwordPwd = $_POST['passwordPwd'];
        $categoryName = $_POST['categoryName'];
        $id = User::where('email', $userData->email)->first()->id;
        $idCat = Category::where('name', $categoryName)->first()->id;
        $passwords = Password::where('user_id', $id)->get();
        foreach ($passwords as &$password) 
        {
            if ($password->title == $passwordTitle) 
            {
                return $this->error(400, 'El nombre de la contraseña ya existe'); 
            }
        }
        
        if ($this->checkLogin($userData->email , $userData->password)) 
        { 
            $password = new Password();
            $password->title = $passwordTitle;
            $password->user_id = $id;
            $password->password = $passwordPwd;
            $password->category_id = $idCat;
            $password->save();                                                                                         
            return $this->success('Contraseña añadida');
        }
        else
        {
            return $this->error(401, "No tienes permisos");
        }
     }
    /**
     * Display the specified resource.
     *
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function show(Password $password)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function edit(Password $password)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Password $password)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Password::destroy($id);
        return $this->success('Contraseña borrada correctamente');
    }
}
