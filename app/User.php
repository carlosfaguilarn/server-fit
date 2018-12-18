<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'name', 'email', 'password', 'edad', 'estatura','peso','genero','actividad'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function calculoNutrimental($userData){
      $edad = $userData['edad'];
      $genero = $userData['genero'];
      $peso = $userData['peso'];
      $estatura = $userData['estatura'];
      $calorias = array(
        'desayuno' => '',
        'comida' => '',
        'cena' => '',
        'aperitivos' => '',
        'total' => '',
      );
      //Personas sedentarias (hace poca actividad física): 1.2
      //Actividad ligera (hace actividad físisca 1 a 3 veces por semana): 1.375
      //Actividad moderada (hace actividad físisca 3 a 5 veces por semana): 1.55
      //Actividad intensa (hace actividad física 6 a 7 veces por semana): 1.725
      //Actividad extremadamente alta (atletas profesionales mucha actividad física): 1.9
      switch ($userData['actividad']) {
        case '1': $actividad = 1.2;  break;
        case '2': $actividad = 1.375;  break;
        case '3': $actividad = 1.55;  break;
        case '4': $actividad = 1.725;  break;
        case '5': $actividad = 1.9;  break;
      }
      //Mujeres  [655 + (9.6 x Peso kg) ] + [ (1.8 x Altura cm) – (4.7 x Edad)] x Factor actividad
      //Hombres  [66 + (13.7 x Peso kg) ] + [ (5 x Altura cm) – (6.8 x Edad)] x Factor actividad
      if($genero == "M"){
        $calorias['total'] = (66 + 13.7*$peso) + (5*$estatura - 6.8*$edad) * $actividad;
      }else if($genero == "F"){
        $calorias['total'] = (655 + 9.6*$peso) + (1.8*$estatura - 4.7*$edad) * $actividad;
      }
      //Truncar a dos decimales
      $calorias['total'] = round($calorias['total']);
      //Desayuno = 20%
      $calorias['desayuno'] =  round($calorias['total'] * 0.2);
      //Comida = 40%
      $calorias['comida'] = round($calorias['total'] * 0.4);
      //Cena = 20%
      $calorias['cena'] = round($calorias['total'] * 0.2);
      //Aperitivos = 20%
      $calorias['aperitivos'] = round($calorias['total'] * 0.2);

      /*
         1-3 gramos por kilo de peso corporal de carbohidratos (hc)
         1-2 gramos de proteína por kilo corporal (pr)
         25 * $actividad = gramos de grasas saludables al día (gr)
      */

      $hc = $peso * 2;
      $pr = $peso * 1.5;
      $gr = 25 * $actividad;

      $respuesta = array(
        'calorias' => $calorias,
        'carbohidratos' => $hc,
        'proteinas' => $pr,
        'grasas' => $gr
      );

      return $respuesta;
    }
}
