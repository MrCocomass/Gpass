<?php
namespace App\Http\Controllers;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    
    protected function login(Request $request)
    {
        if (!isset($_POST['email']) or !isset($_POST['password'])) 
        {
            return $this->error(401, 'Debes rellenar todos los campos')->header('Access-Control-Allow-Origin', '*');
        }
        $email = $_POST['email'];
        $password = $_POST['password'];
        $key = $this->key;
        if (self::checkLogin($email, $password))
        {
            
            $userSave = User::where('email', $email)->first();
            $array = $arrayName = array
            (
                'id' => $userSave->id,
                'email' => $email,
                'password' => $password,
                'name' => $userSave->name
            );
            $token= JWT::encode($array, $key);
            return response($token)->header('Access-Control-Allow-Origin', '*');
        }
        else
        {
            return response("Los datos no son correctos", 403)->header('Access-Control-Allow-Origin', '*');
        }
    }
    public function register (Request $request)
    {
        if (!isset($_POST['name']) or !isset($_POST['email']) or !isset($_POST['password'])) 
        {
            return $this->error(401, 'Debes rellenar todos los campos');
        }
       
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $users = User::where('email', $email)->get();
        foreach ($users as &$user) 
        {
            if ($user->email == $email) 
            {
                return $this->error(400, 'El email ya existe'); 
            }
        }
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            return $this->error(400, 'El nombre solo puede contener caracteres sin espacios en blanco'); 
        } 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error(400, 'Introduzca un email valido'); 
        }
        if (strlen($password) < 8){
            return $this->error(400, 'La contraseña debe tener al  menos 8 caracteres');
        }
    
        
    
        if (!empty($name) && !empty($email) && !empty($password))
        {
            try
            {
                $users = new User();
                $users->name = $name;
                $users->password = $password;
                $users->email = $email;
                $users->role_id = 2;
                $users->save();
            }
            catch(Exception $e)
            {
                return $this->error(2, $e->getMessage());
            }
            
            return $this->error(200, 'Usuario registrado correctamente');
        }
        else
        {
            return $this->error(401, 'Debes rellenar todos los campos');
        }
    }
    public function store(Request $request)
    {
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
