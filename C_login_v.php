<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_login extends CI_Controller{

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_login');
  }

  function index()
  {
    $this->load->view('v_login');
  }

  function loggin()
  {
    $usuario = $this->input->POST('usuario');
    $password = md5($this->input->POST('contrasenia'));

    if(isset($usuario) && !empty($usuario) && isset($password) && !empty($password)){
      $consulta = $this->M_login->consulta_usuarios($usuario,$password);

      if (isset($consulta) && !empty($consulta)) {
        $datos_sesion = array(
          'logeado'         => true,
          'usuario'         => $consulta[0]['nom_usuario'],
          'contrasenia'     => $consulta[0]['password']
        );
        $this->session->set_userdata($datos_sesion);

        // if ($_SESSION['session_activa']=='f') {
          $this->M_login->set_session();
          $this->M_login->registra_sesion($datos_sesion);
        // }

      }else {
        echo 'credenciales incorrectas';
      }
    }
  }
}

?>
