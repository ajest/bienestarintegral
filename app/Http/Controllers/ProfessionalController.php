<?php

namespace App\Http\Controllers;
use App\Professional;
use App\Http\Requests\ProfessionalRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class ProfessionalController extends Controller
{
    
    /**
     * Shows Professionals for Frontend Frameworks
     *
     * @param  integer $page
     * @param  string  $order
     * @param  string  $order_mode
     * @param  integer $rows
     * @return JSON
     */
    public function getAll($page = 1, $order = 'name', $order_mode = 'asc', $rows = 10){
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        
        $allow_order = [
            'name',
            'email',
            'tel',
            'gender',
            'address'
        ];
        
        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){
            $term = '';
            
            if(!empty($_GET['term'])) $term = urldecode($_GET['term']);
            
            $professionals_data = Professional::select('professionals.*')
                            ->where(function ($query) use ($term) {
                            $query
                                ->whereRaw('DATE_FORMAT(created_at, "%d/%m/%Y") like "%' . $term . '%"')
                                ->orWhere('name', 'like', '%' . $term . '%')
                                ->orWhere('email', 'like', '%' . $term . '%')
                                ->orWhere('tel', 'like', '%' . $term . '%')
                                ->orWhere('gender', 'like', '%' . $term . '%')
                                ->orWhere('address', 'like', '%' . $term . '%');
                        })
                        ->orderBy($order, $order_mode)
                        ->paginate($rows);
            
            return response()->json(['professionals' => $professionals_data], 200);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProfessionalRequest  $request
     * @return JSON
     */
    public function store(ProfessionalRequest $request)
    {
        $professional = new Professional;
        $professional->name    = $request->name;
        $professional->email   = $request->email;
        $professional->tel     = $request->tel;
        $professional->gender  = $request->gender;
        $professional->address = $request->address;
        
        $res = $professional->save();
        
        return response()->json(['status' => 'success', 'professional' => $professional], 201);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProfessionalRequest  $request
     * @param  \App\Professional  $professional
     * @return JSON
     */
    public function update(ProfessionalRequest $request, Professional $professional)
    {
        $professional->name   = $request->name;
        $professional->email  = $request->email;
        $professional->tel    = $request->tel;
        $professional->gender = $request->gender;
        $professional->address= $request->address;
        
        $res = $professional->save();
        
        return response()->json(['status' => 'success', 'professional' => $professional], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Professional  $professional
     * @return JSON
     */
    public function destroy(Professional $professional)
    {
        $res = $professional->delete();
        
        return response()->json(['status' => 'success'], 200);
    }
    
    /**
     * Search for a term in indicated table.
     *
     * @param  integer $page
     * @param  integer $rows
     * @return JSON
     */
    public function search($page = 1, $rows = 10){
        
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        
        $term = '';
        
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);
        
        $professionals_data = Professional::select('professionals.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->whereRaw('DATE_FORMAT(created_at, "%d/%m/%Y") like "%' . $term . '%"')
                            ->orWhere('name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%')
                            ->orWhere('tel', 'like', '%' . $term . '%')
                            ->orWhere('gender', 'like', '%' . $term . '%')
                            ->orWhere('address', 'like', '%' . $term . '%');
                    })
                    ->paginate($rows);
        
        $response = ['professionals' => $professionals_data];
        
        return response()->json($response, 200);
    }
    
    /**
     * Signin to the app
     *	@param  \Illuminate\Http\Request  $request
     *
     */
    public function signin(Request $request){
    	$this->validate($request, [
    		'email' => 'required|email',
    		'password' => 'required'
    	]);

    	$credentials = $request->only('email', 'password');

    	try {
    		if (!$token = JWTAuth::attempt($credentials)){
    			return response()->json(['error' => 'Email o contraseña incorrecto/s'], 401);
    		}
    	} catch (JWTException $e) {
    		return response()->json(['error' => 'No se ha podido acceder'], 500);
    	}

        $professional = JWTAuth::toUser($token);

    	return response()->json(['token' => $token, 'professional' => $professional], 200);
    }

    /**
     * Prevents error in route resources
     *
     * @return bool
     */
    public function show()
    {
        return true;
    }
}