<?php

namespace App\Bienestarintegral\Messages;

use Illuminate\Support\Facades\Session;

class OperationMessage{
	
    public function saveOrFailMessage($success = FALSE, $element = 'Ha ocurrido un error inesperado'){
        if($success){
            Session::flash('resultOperation', "Operación exitosa$element");
            Session::flash('typeOperation', 'success');
        }else{
            Session::flash('resultOperation', "Operación errónea$element");
            Session::flash('typeOperation', 'error');
        }
	}

    public function deleteMessage($success = FALSE, $element = 'Ha ocurrido un error inesperado'){
        if($success){
            Session::flash('resultOperation', "Operación exitosa$element");
            Session::flash('typeOperation', 'success');        
        }else{
            Session::flash('resultOperation', "Operación errónea$element");
            Session::flash('typeOperation', 'error');
        }
    }

}