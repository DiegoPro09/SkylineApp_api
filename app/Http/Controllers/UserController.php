<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::all();

        return response()->json($users);
    }

    public function show($id){
        $user = User::find($id);

        if(is_object($user)){
            return $user;
        }else{
            return $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no existe'
            ];
        }
    }

    public function register(Request $request){
        //Recoger los datos del usuario por POST
        $form = $request->all();
        $json = json_encode($form);
        $params = json_encode($json); //OBJETO
        $params_array = json_decode($json, true); //ARRAY

        if(!empty($params) && !empty($params_array)){
            //Limpiar datos
            $params_array = array_map('trim', $params_array); //Para que no se guarden los espacios

            //Validar datos
            $validate = \Validator::make($params_array, [
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'dni' => 'required|numeric|unique:users',
                'phone' => 'numeric',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'verif_password' => 'required',
                'question' => 'required',
                'answer' => 'required',
            ]);

            if($validate->fails()){
                $data = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha podido crear, verifique que ingreso bien los datos',
                    'errors' => $validate->errors()
                ];
            }else{
                if($params_array['verif_password'] === $params_array["password"]){
                    //Cifrar la contraseña
                    $pwd = hash('sha256', $params_array["password"]);

                    //Cifrar la respuesta
                    $answer = hash('sha256', $params_array["answer"]);

                    //Crear el usuario
                    $user = new User;
                    $user->first_name = $params_array['first_name'];
                    $user->last_name = $params_array['last_name'];
                    $user->dni = $params_array['dni'];
                    $user->phone = $params_array['phone'];
                    $user->email = $params_array['email'];
                    $user->password = $pwd;
                    $user->question = $params_array['question'];
                    $user->answer = $answer;
                    $user->state = 0;
                    $user->role_id = 5;

                    //Guarda el usuario
                    $user->save();

                    $data = [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'El usuario se ha creado correctamente',
                        'user' => $user
                    ];
                }else{
                    $data = [
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'Las contraseñas no coinciden',
                    ];
                }
            }
        }else{
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos',
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function login(Request $request){
        $jwtAuth = new \JwtAuth();

        //Recibir datos
        $form = $request->all();
        $json = json_encode($form);
        $params = json_encode($json); //OBJETO
        $params_array = json_decode($json, true); //ARRAY

        //Validar los datos
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'dni' => 'required',
            'password' => 'required',
            'verif_password' => 'required',
            'answer' => 'required',
        ]);

        if($validate->fails()){
            $signup = [
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se podido identificar',
                'errors' => $validate->errors()
            ];
        }else{
            if($params_array['verif_password'] === $params_array['password']){
                //Cifrar la contraseña
                $pwd = hash('sha256', $params_array['password']);
                $answer = hash('sha256', $params_array['answer']);

                //Devolver token
                $signup = $jwtAuth->signup($params_array['email'], $pwd, $answer, $params_array['dni']);

                if(!empty($params->getToken)){
                    $signup = $jwtAuth->signup($params_array['email'], $pwd, $params_array['answer'], true); //Devuelve los datos decodificados
                }
            }else{
                $signup = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Las contraseñas no coinciden'
                ];
            }
        }

        return response()->json($signup, $signup['code']);
    }

    public function update(Request $request){
        //Comprobar usuario identificado
        $token = $request->header('Authorization'); //Recoge el token desde la cabecera Authorization
        $jwtAuth = new \JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        //Recoger los datos por post
        $form = $request->all();
        $json= json_encode($form);
        $params_array = json_decode($json, true);

        if($checkToken && !empty($params_array)){
            //Sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);

            if(!empty($params_array['password']) || !empty($params_array['answer'])){
                if(!empty($params_array['password']) && !empty($params_array['verif_password'])){
                    if($params_array['password'] === $params_array['verif_password']){
                        //Validar los datos
                        $validate = \Validator::make($params_array, [
                            'first_name' => 'required|alpha',
                            'last_name' => 'required|alpha',
                            'dni' => 'required|alpha',
                            'phone' => 'numeric',
                            'email' => 'required|email|unique:users'.$user->sub,
                            'password' => 'required|alpha',
                            'question' => 'required|required'
                        ]);

                        //Cifrar la contraseña
                        $pwd = hash('sha256', $params_array["password"]);
                        $params_array["password"] = $pwd;

                        //Sacar los campos que no se actualizan
                        unset($params_array['id']);
                        unset($params_array['role_id']);
                        unset($params_array['created_at']);
                        unset($params_array['remember_token']);
                        unset($params_array['verif_password']);

                        //Actualizar el usuario
                        $user_update = User::where('id', $user->sub)->update($params_array);

                        //Devolver array con resultado
                        $data = [
                            'status' => 'success',
                            'code' => 200,
                            'message' => 'Usuario actualizado',
                            'v' => $params_array

                        ];
                    }else{
                        unset($params_array['password']);
                        $data = [
                            'code' => 404,
                            'status' => 'error',
                            'message' => 'Las contraseñas no coinciden'
                        ];
                    }
                }

                if(!empty($params_array['answer'])){
                    //Validar los datos
                    $validate = \Validator::make($params_array, [
                        'first_name' => 'required|alpha',
                        'last_name' => 'required|alpha',
                        'dni' => 'required|alpha',
                        'phone' => 'numeric',
                        'email' => 'required|email|unique:users'.$user->sub,
                        'answer' => 'required|alpha',
                        'question' => 'required|required'
                    ]);

                    //Cifrar la contraseña
                    $ans = hash('sha256', $params_array["answer"]);
                    $params_array["answer"] = $ans;

                    //Sacar los campos que no se actualizan
                    unset($params_array['id']);
                    unset($params_array['role_id']);
                    unset($params_array['created_at']);
                    unset($params_array['remember_token']);

                    //Actualizar el usuario
                    $user_update = User::where('id', $user->sub)->update($params_array);

                    //Devolver array con resultado
                    $data = [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Usuario actualizado',
                        'v' => $params_array
                    ];
                }
            }else{
                //Validar los datos
                $validate = \Validator::make($params_array, [
                    'first_name' => 'required|alpha',
                    'last_name' => 'required|alpha',
                    'dni' => 'required|alpha',
                    'phone' => 'numeric',
                    'email' => 'required|email|unique:users'.$user->sub,
                    'question' => 'required|required'
                ]);

                //Sacar los campos que no se actualizan
                unset($params_array['id']);
                unset($params_array['role_id']);
                unset($params_array['created_at']);
                unset($params_array['remember_token']);

                //Actualizar el usuario
                $user_update = User::where('id', $user->sub)->update($params_array);

                //Devolver array con resultado
                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Usuario actualizado',
                    'v' => $params_array

                ];
            }
        }else{
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'Usuario no identificado'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function createUser(Request $request){
        //Comprobar usuario identificado
        $token = $request->header('Authorization'); //Recoge el token desde la cabecera Authorization
        $jwtAuth = new \JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        //Recoger los datos por post
        $form = $request->all();
        $json = json_encode($form);
        $params_array = json_decode($json, true);

        if($checkToken){
            if(!empty($params_array)){
                //Sacar usuario identificado
                $user = $jwtAuth->checkToken($token, true);
                
                if(($user->role_id === 1) && ($user->state === 1)){
                    if((isset($params_array['password']) && !empty($params_array['password'])) && (isset($params_array['verif_password']) && !empty($params_array['verif_password']))){
                        if($params_array['password'] === $params_array['verif_password']){
                            //Validar los datos
                            $validate = \Validator::make($params_array, [
                                'first_name' => 'required|alpha',
                                'last_name' => 'required|alpha',
                                'dni' => 'required|alpha_num|unique:users',
                                'phone' => 'numeric',
                                'email' => 'required|email|unique:users',
                                'password' => 'required',
                                'question' => 'required',
                                'answer' => 'required',
                                'state' => 'required|numeric',
                                'role_id' => 'required|numeric'
                            ]);
                        
                            //Cifrar la contraseña
                            $pwd = hash('sha256', $params_array["password"]);
                            $params_array["password"] = $pwd;
                            
                            //Cifrar la respuesta
                            $answer = hash('sha256', '01234567');
                            $params_array["answer"] = $answer;

                            //CAmpos que no quiero que se guarden
                            unset($params_array['verif_password']);

                            //Crear el usuario
                            $user = new User;
                            $user->first_name = $params_array['first_name'];
                            $user->last_name = $params_array['last_name'];
                            $user->dni = $params_array['dni'];
                            $user->phone = $params_array['phone'];
                            $user->email = $params_array['email'];
                            $user->password = $pwd;
                            $user->question = 'Cual es mi DNI?';
                            $user->answer = $answer;
                            $user->state = $params_array['state'];
                            $user->role_id = $params_array['role_id'];

                            //Guarda el usuario
                            $user->save();

                            $data = [
                                'status' => 'success',
                                'code' => 200,
                                'message' => 'El usuario se ha creado correctamente',
                                'user' => $user
                            ];
                        }else{
                            $data = [
                                'code' => 404,
                                'status' => 'error',
                                'message' => 'Las contraseñas no coinciden'
                            ];
                        }
                    }else{
                        //Validar los datos
                        $validate = \Validator::make($params_array, [
                            'first_name' => 'required|alpha',
                            'last_name' => 'required|alpha',
                            'dni' => 'required|alpha_num|unique:users',
                            'phone' => 'numeric',
                            'email' => 'required|email|unique:users',
                            'question' => 'required',
                            'answer' => 'required',
                            'state' => 'required|numeric',
                            'role_id' => 'required|numeric'
                        ]);
                    
                        //Cifrar la contraseña
                        $pwd = hash('sha256', '123456789');
                        
                        //Cifrar la respuesta
                        $answer = hash('sha256', '01234567');

                        //Crear el usuario
                        $user = new User;
                        $user->first_name = $params_array['first_name'];
                        $user->last_name = $params_array['last_name'];
                        $user->dni = $params_array['dni'];
                        $user->phone = $params_array['phone'];
                        $user->email = $params_array['email'];
                        $user->password = $pwd;
                        $user->question = 'Cual es mi DNI?';
                        $user->answer = $answer;
                        $user->state = $params_array['state'];
                        $user->role_id = $params_array['role_id'];

                        //Guarda el usuario
                        $user->save();

                        $data = [
                            'status' => 'success',
                            'code' => 200,
                            'message' => 'El usuario se ha creado correctamente',
                            'user' => $user
                        ];
                    }
                    
                }else{
                    $data = [
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'Usted no esta autorizado para realizar esta accion'
                    ];
                }
            }else{
                $data = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Campos requeridos sin informacion'
                ];
            }
        }else{
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'Usuario no identificado',
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function updateUser($id, Request $request){
        //Comprobar usuario identificado
        $token = $request->header('Authorization'); //Recoge el token desde la cabecera Authorization
        $jwtAuth = new \JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        //Recoger los datos por post
        $form = $request->all();
        $json = json_encode($form);
        $params_array = json_decode($json, true);

        //Usuario que viene por id
        $userOnUpdate = User::where('id', $id)->first();

        //Comprobar si existe en la tabla UserData
        $userData = UserData::where('user_id', $id)->first();

        if($checkToken){
            if(!empty($params_array)){
                //Sacar usuario identificado
                $user = $jwtAuth->checkToken($token, true);
                
                if($user->role_id === 1){
                    if((isset($params_array['password']) && !empty($params_array['password'])) && (isset($params_array['verif_password']) && !empty($params_array['verif_password']))){
                        if($params_array['password'] === $params_array['verif_password']){
                            //Validar los datos
                            $validate = \Validator::make($params_array, [
                                'first_name' => 'required|alpha',
                                'last_name' => 'required|alpha',
                                'dni' => 'required|alpha_num',
                                'phone' => 'numeric',
                                'email' => 'required|email|unique:users',
                                'password' => 'required',
                                'state' => 'required|numeric',
                                'role_id' => 'required|numeric'
                            ]);
                        
                            //Cifrar la contraseña
                            $pwd = hash('sha256', $params_array["password"]);
                            $params_array["password"] = $pwd; 

                            if($userOnUpdate->role_id < 5){
                                unset($params_array['verif_password']);

                                //Actualizar el usuario
                                $user_update = User::where('id', $id)->update($params_array);

                                //Devolver array con resultado
                                $data = [
                                    'status' => 'success',
                                    'code' => 200,
                                    'user' => $user,
                                    'changes' => $params_array
                                ];

                            }else{
                                $validate = \Validator::make($params_array, [
                                    'book' => 'required|alpha',
                                    'invoice' => 'required|alpha',
                                    'adress' => 'required|alpha_num',
                                    'inscription_date' => 'required|date',
                                    'birthday_date' => 'required|date',
                                    'place_of_birth' => 'required|string',
                                    'nacionality' => 'required|string',
                                    'province' => 'required|string',
                                    'fixed_phone' => 'required|alpha',
                                    'genre_id' => 'required|numeric'
                                ]);
    
                                //Separo en un array diferente los datos basicos de un usuario
                                $user_basic = [
                                    'first_name' => $params_array['first_name'],
                                    'last_name' => $params_array['last_name'],
                                    'email' => $params_array['email'],
                                    'dni' => $params_array['dni'],
                                    'password' => $params_array['password'],
                                    'phone' => $params_array['phone'],
                                    'role_id' => $params_array['role_id'],
                                    'state' => $params_array['state']
                                ];
    
                                //Separo en un array diferente los datos necesarios para el alumno
                                $user_data = [
                                    'book' => $params_array['book'],
                                    'invoice' => $params_array['invoice'],
                                    'adress' => $params_array['adress'],
                                    'inscription_date' => $params_array['inscription_date'],
                                    'birthday_date' => $params_array['birthday_date'],
                                    'place_of_birth' => $params_array['place_of_birth'],
                                    'province' => $params_array['province'],
                                    'nacionality' => $params_array['nacionality'],
                                    'genre_id' => $params_array['genre_id'],
                                    'fixed_phone' => $params_array['fixed_phone'],
                                ];
    
                                if($userData){
                                    //Actualizar el usuario
                                    $user_update = User::where('id', $id)->update($user_basic);
    
                                    //Actualiza datos en la tabla UserData si existe
                                    $user_data_update = UserData::where('user_id', $id)->update($user_data);
    
                                    if($user_update && $user_data_update){
                                        $data = [
                                            'status' => 'success',
                                            'code' => 200,
                                            'message' => 'Usuario actualizado'
                                        ];
                                    }else{
                                        $data = [
                                            'status' => 'error',
                                            'code' => 404,
                                            'message' => 'Ha ocurrido un problema'
                                        ];
                                    }
                                }else{
                                    //Actualizar el usuario
                                    $user_update = User::where('id', $id)->update($user_basic);
    
                                    //Crear el usuarioen la tabla UserData si existe
                                    $user_d = new UserData;
                                    $user_d->user_id = $id; 
                                    $user_d->book = $user_data['book'];
                                    $user_d->invoice = $user_data['invoice'];
                                    $user_d->adress = $user_data['adress'];
                                    $user_d->inscription_date = $user_data['inscription_date'];
                                    $user_d->birthday_date = $user_data['birthday_date'];
                                    $user_d->place_of_birth = $user_data['place_of_birth'];
                                    $user_d->province = $user_data['province'];
                                    $user_d->nacionality = $user_data['nacionality'];
                                    $user_d->genre_id = $user_data['genre_id'];
                                    $user_d->fixed_phone = $user_data['fixed_phone'];
    
                                    //Guarda el usuario
                                    $user_d->save();
    
                                    if($user_update && $user_d){
                                        $data = [
                                            'status' => 'success',
                                            'code' => 200,
                                            'message' => 'Usuario actualizado'
                                        ];
                                    }else{
                                        $data = [
                                            'status' => 'error',
                                            'code' => 404,
                                            'message' => 'ha ocurrido un problema'
                                        ];
                                    }
                                }
                            }
                        }else{
                            $data = [
                                'code' => 404,
                                'status' => 'error',
                                'message' => 'Las contraseñas no coinciden'
                            ];
                        }
                    }else{
                        //Validar los datos
                        $validate = \Validator::make($params_array, [
                            'first_name' => 'required|alpha',
                            'last_name' => 'required|alpha',
                            'dni' => 'required|alpha_num',
                            'phone' => 'numeric',
                            'email' => 'required|email|unique:users',
                            'state' => 'required|numeric',
                            'role_id' => 'required|numeric'
                        ]);

                        if($userOnUpdate->role_id < 5){
                            //Actualizar el usuario
                            $user_update = User::where('id', $id)->update($params_array);

                            //Devolver array con resultado
                            $data = [
                                'status' => 'success',
                                'code' => 200,
                                'user' => $user,
                                'changes' => $params_array
                            ];
                        }else{
                            $validate = \Validator::make($params_array, [
                                'book' => 'required|alpha',
                                'invoice' => 'required|alpha',
                                'adress' => 'required|alpha_num',
                                'inscription_date' => 'required|date',
                                'birthday_date' => 'required|date',
                                'place_of_birth' => 'required|string',
                                'nacionality' => 'required|string',
                                'province' => 'required|string',
                                'fixed_phone' => 'required|alpha',
                                'genre_id' => 'required|numeric'
                            ]);

                            //Separo en un array diferente los datos basicos de un usuario
                            $user_basic = [
                                'first_name' => $params_array['first_name'],
                                'last_name' => $params_array['last_name'],
                                'email' => $params_array['email'],
                                'dni' => $params_array['dni'],
                                'phone' => $params_array['phone'],
                                'role_id' => $params_array['role_id'],
                                'state' => $params_array['state']
                            ];

                            //Separo en un array diferente los datos necesarios para el alumno
                            $user_data = [
                                'book' => $params_array['book'],
                                'invoice' => $params_array['invoice'],
                                'adress' => $params_array['adress'],
                                'inscription_date' => $params_array['inscription_date'],
                                'birthday_date' => $params_array['birthday_date'],
                                'place_of_birth' => $params_array['place_of_birth'],
                                'province' => $params_array['province'],
                                'nacionality' => $params_array['nacionality'],
                                'genre_id' => $params_array['genre_id'],
                                'fixed_phone' => $params_array['fixed_phone'],
                            ];

                            if($userData){
                                //Actualizar el usuario
                                $user_update = User::where('id', $id)->update($user_basic);

                                //Actualiza datos en la tabla UserData si existe
                                $user_data_update = UserData::where('user_id', $id)->update($user_data);

                                if($user_update && $user_data_update){
                                    $data = [
                                        'status' => 'success',
                                        'code' => 200,
                                        'message' => 'Usuario actualizado'
                                    ];
                                }else{
                                    $data = [
                                        'status' => 'error',
                                        'code' => 404,
                                        'message' => 'Ha ocurrido un problema'
                                    ];
                                }
                            }else{
                                //Actualizar el usuario
                                $user_update = User::where('id', $id)->update($user_basic);

                                //Crear el usuarioen la tabla UserData si existe
                                $user_d = new UserData;
                                $user_d->user_id = $id; 
                                $user_d->book = $user_data['book'];
                                $user_d->invoice = $user_data['invoice'];
                                $user_d->adress = $user_data['adress'];
                                $user_d->inscription_date = $user_data['inscription_date'];
                                $user_d->birthday_date = $user_data['birthday_date'];
                                $user_d->place_of_birth = $user_data['place_of_birth'];
                                $user_d->province = $user_data['province'];
                                $user_d->nacionality = $user_data['nacionality'];
                                $user_d->genre_id = $user_data['genre_id'];
                                $user_d->fixed_phone = $user_data['fixed_phone'];

                                //Guarda el usuario
                                $user_d->save();

                                if($user_update && $user_d){
                                    $data = [
                                        'status' => 'success',
                                        'code' => 200,
                                        'message' => 'Usuario actualizado'
                                    ];
                                }else{
                                    $data = [
                                        'status' => 'error',
                                        'code' => 404,
                                        'message' => 'ha ocurrido un problema'
                                    ];
                                }
                            }
                        }
                    }
                }else{
                    $data = [
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'Usted no esta autorizado para realizar esta accion'
                    ];
                }
            }else{
                $data = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Campos requeridos sin informacion'
                ];
            }
        }else{
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'Usuario no identificado',
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request){
        //Comprobar usuario identificado
        $token = $request->header('Authorization'); //Recoge el token desde la cabecera Authorization
        $jwtAuth = new \JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        if($checkToken){
            //Sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);
            
            if($user->role_id === 1){
                //Comprobar si existen los datos
                $user_delete = User::find($id);
                
                if(!empty($user_delete)){
                    if($user_delete->role_id === 5){
                        $user_data_delete = UserData::where('user_id', $id)->first();

                        if($user_data_delete){
                            $user_data_delete->delete();
                            $user_delete->delete();

                            //Devolver el resultado
                            $data = [
                                'code' => 200,
                                'status' => 'success',
                                'Usuario eliminado' => $user
                            ];
                        }else{
                            $user_delete->delete();

                            //Devolver el resultado
                            $data = [
                                'code' => 200,
                                'status' => 'success',
                                'Usuario eliminado' => $user
                            ];
                        }
                    }else{
                        //Borrarlo
                        $user_delete->delete();
                        
                        //Devolver el resultado
                        $data = [
                            'code' => 200,
                            'status' => 'success',
                            'Usuario eliminado' => $user_delete
                        ];
                    }
                }else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'El usuario no existe'
                    ];
                }
            }else{
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Usted no esta autorizado para realizar esta accion'
                ];
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Usuario no identificado'
            ];
        }

        return response()->json($data, $data['code']);
    }
}
