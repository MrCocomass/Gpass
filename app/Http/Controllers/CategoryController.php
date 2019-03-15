<?php
namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\User;
class CategoryController extends Controller
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
        $categories = Category::where('user_id', $id)->get();
        if ($categories->isEmpty()) { 
            return $this->error(400, "No hay categorias.");
        }
        $categoryNames = [];
        $categoryIDs = [];
        foreach ($categories as $category) {
            array_push($categoryNames, $category->name);
            array_push($categoryIDs, $category->id);
            } 
        return response()->json ([
                'categories' => $categoryNames,
                'ids' => $categoryIDs
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
        $categoryName = $_POST['categoryName'];
        $id = User::where('email', $userData->email)->first()->id;
        $categories = Category::where('user_id', $id)->get();
        foreach ($categories as &$category) 
        {
            if ($category->name == $categoryName) 
            {
                return $this->error(400, 'El nombre de la categoria ya existe'); 
            }
        }
        if (!preg_match("/^[a-zA-Z ]*$/",$categoryName)) {
            return $this->error(400, 'El nombre de la categoria solo puede contener caracteres sin espacios en blanco'); 
        }
        if (empty($categoryName)) {
            return $this->error(400, 'Tienes que introducir un nombre para la categoria');
         } 
        if ($this->checkLogin($userData->email , $userData->password)) 
        { 
            $category = new Category();
            $category->name = $categoryName;
            $category->user_id = $id;
            $category->save();
            return $this->success('Categoria creada', $request->categoryName);
        }
        else
        {
            return $this->error(401, "No tienes permisos");
        }
     }
    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return $this->success('Categoria borrada');
    }
}
